# my-alfred-workflows
个人用的workflows
## 使用示例：

* sto 字符串转数组/json。支持默认按换行符,|等分割，可指定分隔符，如：sto @::1@1，也支持query转数组/json
* scase 字符串大小写、驼峰法、下划线、常量等转换
* dtime 时间工具，格式化时间，时间计算等，基准时间支持时间戳和格式化时间，如：1679795969+3year-3days或2023-03-26 01:59:29-3minutes等，时间戳支持精确到秒或毫秒，但毫秒位会被舍弃，格式化时间支持-.:等分割，添加减少时间支持+-year month day hour minute second
* jsonto json转成PHP数组、xml、http query
* htmltojs HTML标签转js字符串
* srandom 生成指定位数的随机字符串，默认10位
* hash hash一个字符串，指定算法格式为 md5::string
* sencode 字符串encode编码:urlencode、HTML实体转义、base64编码、unicode编码等
* sdecode 同sencode相反
* bdfy 百度翻译，需手动配置常量BDFY_APP_ID和BDFY_SECRET，自行去百度翻译申请