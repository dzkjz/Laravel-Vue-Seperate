<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCafeRequest;
use App\Models\Cafe;
use App\Models\CafePhoto;
use App\Models\Tag;
use App\Utilities\GaodeMaps;
use App\Utilities\Tagger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CafesController extends Controller
{
    /*
     |-------------------------------------------------------------------------------
     | Get All Cafes
     |-------------------------------------------------------------------------------
     | URL:            /api/v1/cafes
     | Method:         GET
     | Description:    Gets all of the cafes in the application
    */
    public function getCafes()
    {

        $cafes = Cafe::all()->load('brewMethods')->load('tags:name');

        return response()->json($cafes, 200);

    }

    /*
     |-------------------------------------------------------------------------------
     | Adds a New Cafe
     |-------------------------------------------------------------------------------
     | URL:            /api/v1/cafes
     | Method:         POST
     | Description:    Adds a new cafe to the application
    */
    public function postNewCafe(StoreCafeRequest $request)
    {
//        $data = $request->all();
//        $coordinates = GaodeMaps::geocodeAddress($request->address, $request->city, $request->state);
//        $data['latitude'] = $coordinates['lat'];
//        $data['longitude'] = $coordinates['lng'];
//        $cafe = Cafe::create($data);
//
//        return response()->json($cafe, 201);

        // 已添加的咖啡店
        $addedCafes = [];

        //数据取出
        $data = $request->all();

        // 咖啡店名称
        $name = $data['name'];

        // 咖啡烘焙师
        $roaster = $data['roaster'] ? 1 : 0;
        // 咖啡店网址
        $website = $data['website'];
        // 描述信息
        $description = $data['description'] ?: '';
        // 添加者
        $added_by = $request->user()->id;

        $locations = $data['locations'];
        $locations = json_decode($locations);
        //第一个节点设置为父节点
        $parentNodeData = [
            'name' => $name,
            'roaster' => $roaster,
            'website' => $website,
            'description' => $description,
            'added_by' => $added_by,
            'location_name' => $locations[0]->name,
            'address' => $address = $locations[0]->address,
            'city' => $city = $locations[0]->city,
            'state' => $state = $locations[0]->state,
            'zip' => $locations[0]->zip,
        ];
        $coordinates = GaodeMaps::geocodeAddress($address, $city, $state);
        $parentNodeData['latitude'] = $coordinates['lat'];
        $parentNodeData['longitude'] = $coordinates['lng'];

        $parentNodeCafe = Cafe::create($parentNodeData);

        $photos = $request->file('file');

        if ($photos && $photos->isValid()) {
            $destinationPath = storage_path('app/public/photo' . $parentNodeCafe->id);
            //如果目录不存在则创建
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath);
            }

            //文件名
            $fileName = time() . '-' . $photos->getClientOriginalName();

            //保存文件到目录
            $photos->move($destinationPath, $fileName);

            //在数据库中创建新纪录 保存刚刚上传的文件的位置信息

            CafePhoto::create([
                'cafe_id' => $parentNodeCafe->id,
                'uploaded_by' => auth()->id(),
                'file_url' => $destinationPath . DIRECTORY_SEPARATOR . $fileName,
            ]);

        }

        // 冲泡方法
        $brewMethods = $locations[0]->methodsAvailable;
        // 保存与此咖啡店关联的所有冲泡方法（保存关联关系）
        $parentNodeCafe->brewMethods()->sync($brewMethods);
        // 标签数据
        $tags = $locations[0]->tags;
        // 绑定咖啡店与标签
        Tagger::tagCafe($parentNodeCafe, $tags, auth()->id());

        // 将当前咖啡店数据推送到已添加咖啡店数组
        array_push($addedCafes, $parentNodeCafe->toArray());


        // 第一个索引的位置信息已经使用，从第 2 个位置开始
        if (count($locations) > 1) {
            // 从索引值 1 开始，以为第一个位置已经使用了
            for ($i = 1; $i < count($locations); $i++) {
                // 其它分店信息的获取和保存，与总店共用名称、网址、描述、烘焙师等信息，其他逻辑与总店一致

                //第一个节点设置为父节点
                $childNodeData = [
                    'name' => $name,
                    'roaster' => $roaster,
                    'website' => $website,
                    'description' => $description,
                    'added_by' => $added_by,
                    'location_name' => $locations[$i]->name,
                    'address' => $address = $locations[$i]->address,
                    'city' => $city = $locations[$i]->city,
                    'state' => $state = $locations[$i]->state,
                    'zip' => $locations[$i]->zip,
                ];
                $coordinates = GaodeMaps::geocodeAddress($address, $city, $state);
                $childNodeData['latitude'] = $coordinates['lat'];
                $childNodeData['longitude'] = $coordinates['lng'];

                $childNodeCafe = Cafe::create($childNodeData);

                // 冲泡方法
                $brewMethods = $locations[$i]->methodsAvailable;
                // 保存与此咖啡店关联的所有冲泡方法（保存关联关系）
                $childNodeCafe->brewMethods()->sync($brewMethods);
                // 标签数据
                $tags = $locations[$i]->tags;
                // 绑定咖啡店与标签
                Tagger::tagCafe($childNodeCafe, $tags, auth()->id());
                // 将当前咖啡店数据推送到已添加咖啡店数组
                array_push($addedCafes, $childNodeCafe->toArray());
            }

        }
        return response()->json($addedCafes, 201);

    }

    /*
     |-------------------------------------------------------------------------------
     | Get An Individual Cafe
     |-------------------------------------------------------------------------------
     | URL:            /api/v1/cafes/{id}
     | Method:         GET
     | Description:    Gets an individual cafe
     | Parameters:
     |   $id   -> ID of the cafe we are retrieving
    */
    public function getCafe(Cafe $cafe)
    {
        $cafe = $cafe->load('brewMethods')
            ->load('authUserLike')//使返回给咖啡店详情页的数据中包含用户是否喜欢咖啡店
            ->load('tags');
        return response()->json($cafe, 200);
    }


    public function postLikeCafe($cafeId)
    {
        $cafe = Cafe::find($cafeId);

        $cafe->likesBy()->attach(auth()->id());

        return response()->json(['cafe_liked' => true], 201);
    }

    public function deleteLikeCafe($cafeId)
    {
        $cafe = Cafe::find($cafeId);

        $cafe->likesBy()->detach(auth()->id());

        return response()->json(['cafe_liked_cancelled' => true], 204);

    }

    /**
     * 给咖啡店添加标签
     * @param $request
     * @param $cafeID
     * @return JsonResponse
     */
    public function postAddTags(Request $request, $cafeId)
    {
        $tags = $request->input('tags');
        $cafe = Cafe::find($cafeId);
        // 处理新增标签并建立标签与咖啡店之间的关联
        Tagger::tagCafe($cafe, $tags, auth()->id());

        $cafe = $cafe->load('brewMethod')
            ->load('authUserLike')
            ->load('tags');

        return response()->json($cafe, 201);
    }

    /**
     * 删除咖啡店上的指定标签
     * @param $cafeID
     * @param $tagID
     * @return Response
     */
    public function deleteCafeTag($cafeId, $tagId)
    {
        DB::table('cafes_users_tags')
            ->where('cafe_id', $cafeId)
            ->where('tag_id', $tagId)
            ->where('user_id', auth()->id())
            ->delete();

        return response()->json(null, 204);
    }
}
