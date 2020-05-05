export const CafeTextFilter = {
    methods: {
        processCafeTextFilter(cafe, text) {
            //筛选文本不为空时才会处理
            if (text.length > 0) {
                //如果咖啡店名称、位置、地址或城市与筛选文本匹配，则返回true，否则返回false
                if (cafe.name.toLowerCase().match('[^,]*' + text.toLowerCase() + '[,$]*')
                    || cafe.location_name.toLowerCase().match('[^,]*' + text.toLowerCase() + '[,$]*')
                    || cafe.address.toLowerCase().match('[^,]*' + text.toLowerCase() + '[,$]*')
                    || cafe.city.toLowerCase().match('[^,]*' + text.toLowerCase() + '[,$]*')
                ) {
                    return true;
                } else {
                    return false;
                }
            } else {
                //如果文本筛选字段为空，则显示咖啡店
                return true;
            }
        }
    }
}
