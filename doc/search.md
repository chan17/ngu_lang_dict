# 搜索

搜索一個詞或者字



---



## 搜索列表

### HTTP 请求

```
GET /v1/search
```

### URL 参数

参数放url内

参数名    |必填| 值类型     | 说明
--------- |--| ---------- | ----------------------------
word      | 是| string     | 搜索的字，繁體簡體都行
source |是|int|字詞 來源。1 低地吳語 2 吳語方言 3 古今東亞
page_no   |否|Int| 分页页码（默认 1）|




### 请求体


参数名|值类型|说明
 -- | ------|------ 




### 响应体

返回数组

```
{
  id: int // id
  zh_tw: string // 繁體字
  zh_cn: string // 簡體字
  phonetic: array //音標, json 數組
  explanation: array // 字詞解釋
}
```