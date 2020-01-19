define({ "api": [
  {
    "type": "post",
    "url": "Boutique/courseIndex",
    "title": "精品课程",
    "version": "0.1.0",
    "group": "Boutique",
    "description": "<p>精品课程</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "search",
            "description": "<p>搜索关键词  可选</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "type_id",
            "description": "<p>类型id  可选</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "sort",
            "description": "<p>排序方式（1为按时间排序 2为按热度排序）可选</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例1",
          "content": "{\n   \"code\": 200, \n   \"data\": {\n       \"search\": \"课程\", //搜索词\n       \"count\": 1, //课程数\n       \"course\": [\n           {\n               \"id\": 6, //课程id\n               \"title\": \"课程6\", //课程标题\n               \"duration\": \"00:10\", //视频时长\n               \"video_img\": \"/muke/public/uploads/20191121/f49147ef4112f3430afe0f7cd6647fba.jpeg\", //缩略图\n               \"type\": \"社区治理\", //类型\n               \"teacher_name\": \"讲师1\",//讲师姓名\n               \"course_type\":1//1视频课程  2图文课程\n           }\n       ]\n   }\n}",
          "type": "json"
        },
        {
          "title": "成功返回示例2",
          "content": "{\n   \"code\": 404, \n   \"msg\":\"抱歉，未搜索到相关课程！\",\n   \"data\": {\n       \"search\": \"课程\", //搜索词\n       \"count\": 0, //课程数\n   }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Boutique.php",
    "groupTitle": "Boutique",
    "name": "PostBoutiqueCourseindex",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Boutique/courseIndex"
      }
    ]
  },
  {
    "type": "post",
    "url": "Details/collection",
    "title": "收藏",
    "version": "0.1.0",
    "group": "Details",
    "description": "<p>收藏</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "course_id",
            "description": "<p>课程id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例1",
          "content": "\n{\n   \"code\":200,\n   \"msg\":\"收藏成功\",\n   \"status\":\"1\",\n   \"number\":\"1\",//收藏数\n}",
          "type": "json"
        },
        {
          "title": "成功返回示例2",
          "content": "\n{\n   \"code\":201,\n   \"msg\":\"取消收藏成功\",\n   \"status\":\"0\",\n   \"number\":\"1\",//收藏数\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "\n{\n\n   \"code\": 0,\n\n   \"msg\": \"缺少course_id\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3:",
          "content": "\n{\n\n   \"code\": -3,\n\n   \"msg\": \"暂无该课程或课程已下架\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4:",
          "content": "\n{\n\n   \"code\": -2,\n\n   \"msg\": \"数据错误，请重试！\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例5:",
          "content": "\n{\n\n   \"code\": -4,\n\n   \"msg\": \"无权限\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Details.php",
    "groupTitle": "Details",
    "name": "PostDetailsCollection",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Details/collection"
      }
    ]
  },
  {
    "type": "post",
    "url": "Details/courseIntegralAdd",
    "title": "添加视频课程积分",
    "version": "0.1.0",
    "group": "Details",
    "description": "<p>添加课程积分</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "course_id",
            "description": "<p>课程id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "{\n   \"code\":200,\n   \"msg\":\"成功获取课程积分！\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "\n{\n\n   \"code\": -2,\n\n   \"msg\": \"已获取过本课程积分！\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3:",
          "content": "\n{\n\n   \"code\": -3,\n\n   \"msg\": \"数据错误，请重试！\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4:",
          "content": "\n{\n\n   \"code\": 0,\n\n   \"msg\": \"缺少course_id\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例5:",
          "content": "\n{\n\n   \"code\": -4,\n\n   \"msg\": \"无权限\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例6:",
          "content": "\n{\n\n   \"code\": -5,\n\n   \"msg\": \"课程积分功能已关闭！/暂无该课程或课程已下架\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Details.php",
    "groupTitle": "Details",
    "name": "PostDetailsCourseintegraladd",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Details/courseIntegralAdd"
      }
    ]
  },
  {
    "type": "post",
    "url": "Details/dayIntegralAdd",
    "title": "添加视频课程每日积分",
    "version": "0.1.0",
    "group": "Details",
    "description": "<p>添加课程每日积分</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "course_id",
            "description": "<p>课程id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "{\n   \"code\":200,\n   \"msg\":\"成功获取每日课程积分！\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "\n{\n\n   \"code\": -2,\n\n   \"msg\": \"已获取过每日课程积分！\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3:",
          "content": "\n{\n\n   \"code\": -3,\n\n   \"msg\": \"数据错误，请重试！\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4:",
          "content": "\n{\n\n   \"code\": 0,\n\n   \"msg\": \"缺少course_id\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例5:",
          "content": "\n{\n\n   \"code\": -4,\n\n   \"msg\": \"无权限\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例6:",
          "content": "\n{\n\n   \"code\": -5,\n\n   \"msg\": \"每日积分功能已关闭！/暂无该课程或课程已下架\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Details.php",
    "groupTitle": "Details",
    "name": "PostDetailsDayintegraladd",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Details/dayIntegralAdd"
      }
    ]
  },
  {
    "type": "post",
    "url": "Details/details",
    "title": "视频详情及相应问题",
    "version": "0.1.0",
    "group": "Details",
    "description": "<p>视频详情及相应问题</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "course_id",
            "description": "<p>课程id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例1",
          "content": "\n{\n   \"code\": 200, \n   \"data\": {\n       \"id\": 2, //课程id\n       \"title\": \"课程2\", //课程标题\n       \"video\": \"\", //视频地址\n       \"type_id\": \"2\", //课程类型二级类型id\n       \"type_fid\": \"1\", //课程类型一级类型id\n       \"type\": \"社区实务\", //课程类型\n       \"teacher_name\": \"讲师1\", //讲师姓名\n       \"head_img\": null, //讲师头像\n       \"view_number\": 102, //学习人数\n       \"introduction\": \"课程2课程2课程2\", //课程介绍\n       \"teacher_introduce\": \"高级讲师\", //讲师介绍\n       \"seo_title\": \"php从初级到高级\", //seo标题\n       \"seo_key\": \"php从初级到高级\", //seo关键词\n       \"seo_des\": \"php从初级到高级\", //seo描述    \n       \"playtime\": \"00:00\", //开始播放的节点\n       \"collect\": \"0\", //0未收藏 1已收藏 \n       \"is_integral\":\"1\",//0本课程没有课程积分 观看10分钟可添加  1 今天已有课程积分或关闭添加课程积分功能\n       \"is_integral_day\":\"1\",//0今天没有添加每日课程积分 观看10分钟可添加  1 今天已有每日课程积分或关闭添加每日课程积分功能\n       \"questions\": [ //问题\n           {\n               \"id\": 2, //问题id\n               \"question_title\": \"问题2\", //问题标题\n               \"time_node\": \"02:00\", //问题出现的时间节点\n               \"answer_type\": \"1\", //1单选 2多选\n               \"option\": [ //选项\n                   {\n                       \"id\": 25, //选项id\n                       \"choice_text\": \"选项1\" //选项内容\n                   }, \n                   {\n                       \"id\": 26, \n                       \"choice_text\": \"选项2\"\n                   }, \n                   {\n                       \"id\": 27, \n                       \"choice_text\": \"选项3\"\n                   }, \n                   {\n                       \"id\": 28, \n                       \"choice_text\": \"选项4\"\n                   }\n               ]\n           }, \n           {\n               \"id\": 1, \n               \"question_title\": \"问题1\", \n               \"time_node\": \"05:00\", \n               \"answer_type\": \"1\", //1单选 2多选\n               \"option\": [\n                   {\n                       \"id\": 29, \n                       \"choice_text\": \"选项1\"\n                   }, \n                   {\n                       \"id\": 30, \n                       \"choice_text\": \"选项2\"\n                   }, \n                   {\n                       \"id\": 31, \n                       \"choice_text\": \"选项3\"\n                   }, \n                   {\n                       \"id\": 32, \n                       \"choice_text\": \"选项4\"\n                   }\n               ]\n           }, \n           {\n               \"id\": 3, \n               \"question_title\": \"问题3\", \n               \"time_node\": \"05:00\", \n               \"answer_type\": \"1\", //1单选 2多选\n               \"option\": [\n                   {\n                       \"id\": 21, \n                       \"choice_text\": \"选项1\"\n                   }, \n                   {\n                       \"id\": 22, \n                       \"choice_text\": \"选项2\"\n                   }, \n                   {\n                       \"id\": 23, \n                       \"choice_text\": \"选项3\"\n                   }, \n                   {\n                       \"id\": 24, \n                       \"choice_text\": \"选项4\"\n                   }\n               ]\n           }\n       ]\n   }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "\n{\n\n   \"code\": 0,\n\n   \"msg\": \"缺少course_id\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3:",
          "content": "\n{\n\n   \"code\": -3,\n\n   \"msg\": \"暂无该课程或课程已下架\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4:",
          "content": "\n{\n   \"code\": -4, \n   \"data\": {\n       \"id\": 2, //课程id\n       \"title\": \"课程2\", //课程标题\n       \"video\": \"\", //视频地址\n       \"type_id\": \"2\", //课程类型二级类型id\n       \"type_fid\": \"1\", //课程类型一级类型id\n       \"type\": \"社区实务\", //课程类型\n       \"teacher_name\": \"讲师1\", //讲师姓名\n       \"head_img\": null, //讲师头像\n       \"view_number\": 102, //学习人数\n       \"introduction\": \"课程2课程2课程2\", //课程介绍\n       \"teacher_introduce\": \"高级讲师\", //讲师介绍\n       \"seo_title\": \"php从初级到高级\", //seo标题\n       \"seo_key\": \"php从初级到高级\", //seo关键词\n       \"seo_des\": \"php从初级到高级\", //seo描述    \n       \"collect\": \"0\", //0未收藏 1已收藏 \n       \"is_integral\":\"1\",//不可添加课程积分\n       \"is_integral_day\":'1',//不可添加每日积分\n   }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Details.php",
    "groupTitle": "Details",
    "name": "PostDetailsDetails",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Details/details"
      }
    ]
  },
  {
    "type": "post",
    "url": "Details/history",
    "title": "播放视频时记录历史",
    "version": "0.1.0",
    "group": "Details",
    "description": "<p>播放视频时记录历史</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "course_id",
            "description": "<p>课程id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例1",
          "content": "\n{\n   \"code\":200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "\n{\n\n   \"code\": 0,\n\n   \"msg\": \"缺少course_id\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3:",
          "content": "\n{\n\n   \"code\": -5,\n\n   \"msg\": \"暂无该课程或课程已下架\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4:",
          "content": "\n{\n\n   \"code\": -3,\n\n   \"msg\": \"数据错误，请重试！\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例5:",
          "content": "\n{\n\n   \"code\": -4,\n\n   \"msg\": \"无权限\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Details.php",
    "groupTitle": "Details",
    "name": "PostDetailsHistory",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Details/history"
      }
    ]
  },
  {
    "type": "post",
    "url": "Details/learnTimeAdd",
    "title": "记录学习时长",
    "version": "0.1.0",
    "group": "Details",
    "description": "<p>记录学习时长</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "{\n   \"code\":200,\n   \"msg\":\"记录成功！\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3:",
          "content": "\n{\n\n   \"code\": -2,\n\n   \"msg\": \"数据错误，请重试！\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Details.php",
    "groupTitle": "Details",
    "name": "PostDetailsLearntimeadd",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Details/learnTimeAdd"
      }
    ]
  },
  {
    "type": "post",
    "url": "Details/readStaging",
    "title": "读取暂存作业",
    "version": "0.1.0",
    "group": "Details",
    "description": "<p>读取暂存作业</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "course_id",
            "description": "<p>课程id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回搜索结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\":200,\n   \"data\":{\n       \"worktitle\":\"标题\",//作业标题\n       \"workcontent\":\"内容\",//作业内容\n   }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "{\n\n   \"code\": 0,\n\n   \"msg\": \"缺少course_id/暂无该课程或课程已下架\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3:",
          "content": "{\n\n   \"code\": -4,\n\n   \"msg\": \"无权限\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Details.php",
    "groupTitle": "Details",
    "name": "PostDetailsReadstaging",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Details/readStaging"
      }
    ]
  },
  {
    "type": "post",
    "url": "Details/recommend",
    "title": "推荐课程",
    "version": "0.1.0",
    "group": "Details",
    "description": "<p>推荐课程</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "{\n   \"code\":200,\n   \"data\":[\n       {\n           \"id\":7,//课程id\n           \"title\":\"测试tag课程\", //课程标题\n           \"duration\":\"\",//课程时长\n           \"video_img\":\"\",//缩略图\n           \"type\":\"基础知识\",//课程类型\n           \"teacher_name\":\"讲师1\"//讲师姓名\n           \"course_type\": \"1\" //1 视频课程 2 图文课程\n       },\n       {\n           \"id\":6,\n           \"title\":\"课程6\",\n           \"duration\":\"00:10\",\n           \"video_img\":\"/muke/public/uploads/20191121/f49147ef4112f3430afe0f7cd6647fba.jpeg\",\n           \"type\":\"社区实务\",\n           \"teacher_name\":\"讲师1\"\n           \"course_type\": \"1\" \n       },\n       {\n           \"id\":5,\n           \"title\":\"课程5\",\n           \"duration\":\"00:10\",\n           \"video_img\":\"/muke/public/uploads/20191121/aafb91729f8f1622384028da6c328eef.jpeg\",\n           \"type\":\"政策解读\",\n           \"teacher_name\":\"讲师1\"\n           \"course_type\": \"1\" \n       },\n       {\n           \"id\":4,\n           \"title\":\"课程3\",\n           \"duration\":\"\",\n           \"video_img\":\"/muke/public/uploads/20191121/de6f951f399540b8b1031d3a6b7da6f0.jpg\",\n           \"type\":\"政策解读\",\n           \"teacher_name\":\"讲师1\"\n           \"course_type\": \"1\" \n       }\n   ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Details.php",
    "groupTitle": "Details",
    "name": "PostDetailsRecommend",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Details/recommend"
      }
    ]
  },
  {
    "type": "post",
    "url": "Details/relevant",
    "title": "相关课程",
    "version": "0.1.0",
    "group": "Details",
    "description": "<p>相关课程</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "course_id",
            "description": "<p>课程id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "{\n   \"code\":200,\n   \"data\":[\n       {\n           \"id\":6, //课程id\n           \"title\":\"课程6\", //课程标题\n           \"duration\":\"00:10\", //课程时长\n           \"video_img\":\"/muke/public/uploads/20191121/f49147ef4112f3430afe0f7cd6647fba.jpeg\", //缩略图\n           \"type\":\"社区实务\", //课程类型\n           \"teacher_name\":\"讲师1\" //讲师姓名\n           \"course_type\": \"1\" \n\n       }\n   ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "\n{\n\n   \"code\": 0,\n\n   \"msg\": \"缺少course_id\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3:",
          "content": "\n{\n\n   \"code\": -3,\n\n   \"msg\": \"暂无该课程或课程已下架\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Details.php",
    "groupTitle": "Details",
    "name": "PostDetailsRelevant",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Details/relevant"
      }
    ]
  },
  {
    "type": "post",
    "url": "Details/share",
    "title": "分享",
    "version": "0.1.0",
    "group": "Details",
    "description": "<p>分享</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "course_id",
            "description": "<p>课程id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\":200,\n   \"msg\":\"分享成功\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "\n{\n\n   \"code\": 0,\n\n   \"msg\": \"缺少course_id\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3:",
          "content": "\n{\n\n   \"code\": -3,\n\n   \"msg\": \"暂无该课程或课程已下架\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4:",
          "content": "\n{\n\n   \"code\": -2,\n\n   \"msg\": \"数据错误，请重试！\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例5:",
          "content": "\n{\n\n   \"code\": -4,\n\n   \"msg\": \"无权限\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Details.php",
    "groupTitle": "Details",
    "name": "PostDetailsShare",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Details/share"
      }
    ]
  },
  {
    "type": "post",
    "url": "Details/stagingDel",
    "title": "删除暂存的作业",
    "version": "0.1.0",
    "group": "Details",
    "description": "<p>删除暂存的作业</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "course_id",
            "description": "<p>课程id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例1",
          "content": "\n{\n   \"code\":200,\n   \"msg\"=>\"操作成功\" //提示信息  \n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "\n{\n\n   \"code\": 0,\n\n   \"msg\": \"缺少course_id\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3:",
          "content": "\n{\n\n   \"code\": -2,\n\n   \"msg\": \"数据错误，请重试！\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4:",
          "content": "\n{\n\n   \"code\": -3,\n\n   \"msg\": \"暂无该课程或课程已下架/您没有暂存的作业！/该课程暂无作业\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例5:",
          "content": "\n{\n\n   \"code\": -4,\n\n   \"msg\": \"无权限\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Details.php",
    "groupTitle": "Details",
    "name": "PostDetailsStagingdel",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Details/stagingDel"
      }
    ]
  },
  {
    "type": "post",
    "url": "Details/subwork",
    "title": "提交作业",
    "version": "0.1.0",
    "group": "Details",
    "description": "<p>提交作业</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "course_id",
            "description": "<p>课程id</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "title",
            "description": "<p>作业标题</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "content",
            "description": "<p>作业内容</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>1 提交  2暂存</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例1",
          "content": "\n{\n   \"code\":200,\n   \"msg\"=>\"提交成功\" //提示信息  \n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "\n{\n\n   \"code\": 0,\n\n   \"msg\": \"缺少course_id/缺少title/缺少content\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3:",
          "content": "\n{\n\n   \"code\": -2,\n\n   \"msg\": \"数据错误，请重试！/暂无该课程或课程已下架/该课程暂无作业\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4:",
          "content": "\n{\n\n   \"code\": -3,\n\n   \"msg\": \"不可以重复提交作业\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例5:",
          "content": "\n{\n\n   \"code\": -4,\n\n   \"msg\": \"无权限\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例6:",
          "content": "\n{\n\n   \"code\": -5,\n\n   \"msg\": \"内容含敏感词语\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Details.php",
    "groupTitle": "Details",
    "name": "PostDetailsSubwork",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Details/subwork"
      }
    ]
  },
  {
    "type": "post",
    "url": "Details/textDetails",
    "title": "图文课程详情",
    "version": "0.1.0",
    "group": "Details",
    "description": "<p>图文课程详情</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "course_id",
            "description": "<p>课程id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "{\n   \"code\":200,\n   \"data\":{\n       \"id\":11, //课程id\n       \"title\":\"4\", //课程标题\n       \"content\":\"\", //课程内容\n       \"teacher_name\":\"讲师1\", //讲师姓名\n       \"head_img\":\"/muke/public/uploads/20191207/5a587e758f14774763260b477bab35fa.jpeg\", //讲师头像\n       \"view_number\":5, //学习人数\n       \"teacher_introduce\":\"高级讲师\", //讲师介绍\n       \"seo_title\":\"课程\", //seo标题\n       \"seo_key\":\"课程\", //seo关键词\n       \"seo_des\":\"11\", //seo描述\n       \"is_work\":1, //是否有作业 1是0否\n       \"upload_time\":\"2019-11-22 11:27\", //发布时间\n       \"tag_id\":[ //tag标签\n           \"政策解读\"\n       ],\n       \"status\":10, //审核中  未提交作业  数字为分数\n       \"is_subwork\":\"1\", //是否提交  1是 0否\n       \"is_collect\":\"0\",//是否收藏  1是 0否\n       \"is_like\":\"0\", //是否点赞  1是 2否\n       \"collection_number\":\"1\", 收藏数\n       \"like_number\":\"1\",点赞数\n   }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "\n{\n\n   \"code\": 0,\n\n   \"msg\": \"缺少course_id\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3:",
          "content": "\n{\n\n   \"code\": -3,\n\n   \"msg\": \"暂无该课程或课程已下架/数据错误，请重试！\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4:",
          "content": "\n{\n\n   \"code\": -4,\n\n   \"msg\": \"无权限\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Details.php",
    "groupTitle": "Details",
    "name": "PostDetailsTextdetails",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Details/textDetails"
      }
    ]
  },
  {
    "type": "post",
    "url": "Details/textlike",
    "title": "图文课程点赞",
    "version": "0.1.0",
    "group": "Details",
    "description": "<p>图文课程点赞</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "course_id",
            "description": "<p>课程id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例1",
          "content": "\n{\n   \"code\":200,\n   \"msg\"=>\"点赞成功\" //提示信息  点赞成功 取消点赞成功\n   \"number\":\"1\",点赞数量\n   \"status\":\"1\" //状态 1点赞  0取消点赞\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "\n{\n\n   \"code\": 0,\n\n   \"msg\": \"缺少course_id\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3:",
          "content": "\n{\n\n   \"code\": -3,\n\n   \"msg\": \"暂无该课程或课程已下架\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4:",
          "content": "\n{\n\n   \"code\": -2,\n\n   \"msg\": \"数据错误，请重试！\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例5:",
          "content": "\n{\n\n   \"code\": -4,\n\n   \"msg\": \"无权限\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Details.php",
    "groupTitle": "Details",
    "name": "PostDetailsTextlike",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Details/textlike"
      }
    ]
  },
  {
    "type": "post",
    "url": "Details/totals",
    "title": "提交问题",
    "version": "0.1.0",
    "group": "Details",
    "description": "<p>提交问题</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "question_id",
            "description": "<p>问题id</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "answer_id",
            "description": "<p>答案id   字符串 答案id用,隔开</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "course_id",
            "description": "<p>课程id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例1",
          "content": "\n{\n   \"code\":200,\n   \"msg\":\"回答正确\",\n   \"data\":{\n       \"question_title\":\"问题1\",\n       \"right_answer\":[\"选项4\",\"选项4\",\"选项4\"]\n   }\n}",
          "type": "json"
        },
        {
          "title": "成功返回示例2",
          "content": "\n{\n   \"code\":201,\n   \"msg\":\"回答错误\",\n   \"data\":{\n       \"question_title\":\"问题1\",\n       \"right_answer\":[\"选项4\",\"选项4\",\"选项4\"]\n   }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "\n{\n\n   \"code\": 0,\n\n   \"msg\": \"缺少question_id\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3:",
          "content": "\n{\n\n   \"code\": -2,\n\n   \"msg\": \"缺少answer_id\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3:",
          "content": "\n{\n\n   \"code\": -3,\n\n   \"msg\": \"数据错误，请重试！\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4:",
          "content": "\n{\n\n   \"code\": -4,\n\n   \"msg\": \"请勿重复答题！\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Details.php",
    "groupTitle": "Details",
    "name": "PostDetailsTotals",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Details/totals"
      }
    ]
  },
  {
    "type": "post",
    "url": "Index/ad",
    "title": "轮播图",
    "version": "0.1.0",
    "group": "Index",
    "description": "<p>轮播图</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "{\n   \"code\": 200, \n   \"data\": [\n       {\n           \"name\": \"社工基础知识从0到100\",  //广告名称\n           \"pic\": \"/muke/public/uploads/20191126/743c1818a41ed24314cb0d222e84a4d9.jpg\", //轮播图\n           \"parm_id\": \"2\", //参数id\n           \"parm_type_id\": \"2\",  //广告类型id  1 视频课程广告 2图文类型广告 3单页广告\n       }, \n       {\n           \"name\": \"社区慕课正式开课了\", \n           \"pic\": \"/muke/public/uploads/20191126/20b685315104dfb23622fe9e333e6f1e.jpg\",\n           \"parm_id\": \"3\",\n           \"parm_type_id\": \"1\",\n       }, \n       {\n           \"name\": \"快来注册吧\", \n           \"pic\": \"/cltphp/public/uploads/20180611/814e5f76ef5dce49dfd3dce771631ecf.png\",\n           \"parm_id\": \"4\",\n           \"parm_type_id\": \"3\",\n       }\n   ]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Index.php",
    "groupTitle": "Index",
    "name": "PostIndexAd",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Index/ad"
      }
    ]
  },
  {
    "type": "post",
    "url": "Index/column",
    "title": "栏目列表",
    "version": "0.1.0",
    "group": "Index",
    "description": "<p>栏目列表</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "{\n   \"code\":200,\n   \"data\":[\n       {\n           \"id\":1,//一级栏目id\n           \"column_name\":\"关于我们\",//栏目名称\n           \"url\":\"/about.html\",//链接\n           \"seo_title\":\"1\",//seo标题\n           \"seo_key\":\"1\",//seo关键词\n           \"seo_des\":\"2\",//seo描述\n           \"is_page\":1,//是否是内容单页 1是 2不是\n           \"column_two\":[\n               {\n                   \"id\":2,//二级栏目id\n                   \"column_name\":\"联系我们\",\n                   \"url\":\"#\",\n                   \"seo_title\":\"1\",\n                   \"seo_key\":\"1\",\n                   \"seo_des\":\"1\",\n                   \"is_page\":0\n               }\n           ]\n       },\n       {\n           \"id\":4,\n           \"column_name\":\"讲师招募\",\n           \"url\":\"/join.html\",\n           \"seo_title\":\"讲师招募\",\n           \"seo_key\":\"讲师招募\",\n           \"seo_des\":\"讲师招募\",\n           \"is_page\":0,\n           \"column_two\":[\n\n           ]\n       }\n   ]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Index.php",
    "groupTitle": "Index",
    "name": "PostIndexColumn",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Index/column"
      }
    ]
  },
  {
    "type": "post",
    "url": "Index/courseIndex",
    "title": "首页课程",
    "version": "0.1.0",
    "group": "Index",
    "description": "<p>首页课程</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200,\n   \"data\": {\n       \"new\": [ //最新课程\n           {\n               \"id\": 7, //课程id\n               \"title\": \"测试tag课程\", //课程标题\n               \"duration\": \"\", //视频时长\n               \"video_img\": \"\", //缩略图\n               \"type\": \"基础知识\", //课程类型\n               \"teacher_name\": \"讲师1\", //讲师姓名\n               \"course_type\": \"1\"  // 1 视频课程 2 图文课程\n           },\n           {\n               \"id\": 6,\n               \"title\": \"课程6\",\n               \"duration\": \"00:10\",\n               \"video_img\": \"/muke/public/uploads/20191121/f49147ef4112f3430afe0f7cd6647fba.jpeg\",\n               \"type\": \"社区治理\",\n               \"teacher_name\": \"讲师1\"\n               \"course_type\": \"1\" \n           },\n           {\n               \"id\": 5,\n               \"title\": \"课程5\",\n               \"duration\": null,\n               \"video_img\": \"/muke/public/uploads/20191121/aafb91729f8f1622384028da6c328eef.jpeg\",\n               \"type\": \"政策解读\",\n               \"teacher_name\": \"讲师1\"\n               \"course_type\": \"1\" \n           },\n           {\n               \"id\": 4,\n               \"title\": \"课程3\",\n               \"duration\": null,\n               \"video_img\": \"/muke/public/uploads/20191121/de6f951f399540b8b1031d3a6b7da6f0.jpg\",\n               \"type\": \"政策解读\",\n               \"teacher_name\": \"讲师1\"\n               \"course_type\": \"1\" \n           }\n       ],\n       \"all\": [  //所有类型及课程\n           {  \n               \"id\": 1, //类型id\n               \"type\": \"全能社工\", //课程类型（一级）\n               \"fid\": 0,\n               \"data\": [\n                   {\n                       \"id\": 7, //课程id\n                       \"title\": \"测试tag课程\", //课程标题\n                       \"duration\": \"\", //时长\n                       \"video_img\": \"\", //缩略图\n                       \"type\": \"基础知识\",  //课程类型（二级）\n                       \"teacher_name\": \"讲师1\" //讲师姓名\n               \t\t \"course_type\": \"1\" \n                   },\n                   {\n                       \"id\": 6,\n                       \"title\": \"课程6\",\n                       \"duration\": \"00:10\",\n                       \"video_img\": \"/muke/public/uploads/20191121/f49147ef4112f3430afe0f7cd6647fba.jpeg\",\n                       \"type\": \"社区治理\",\n                       \"teacher_name\": \"讲师1\"\n               \t\t \"course_type\": \"1\" \n                   },\n                   {\n                       \"id\": 5,\n                       \"title\": \"课程5\",\n                       \"duration\": null,\n                       \"video_img\": \"/muke/public/uploads/20191121/aafb91729f8f1622384028da6c328eef.jpeg\",\n                       \"type\": \"政策解读\",\n                       \"teacher_name\": \"讲师1\"\n               \t\t \"course_type\": \"1\" \n                   },\n                   {\n                       \"id\": 4,\n                       \"title\": \"课程3\",\n                       \"duration\": null,\n                       \"video_img\": \"/muke/public/uploads/20191121/de6f951f399540b8b1031d3a6b7da6f0.jpg\",\n                       \"type\": \"政策解读\",\n                       \"teacher_name\": \"讲师1\"\n               \t\t \"course_type\": \"1\" \n                   }\n               ]\n           },\n           {\n               \"id\": 6,\n               \"type\": \"领头雁\",\n               \"fid\": 0,\n               \"data\": []\n           }\n       ]\n   }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Index.php",
    "groupTitle": "Index",
    "name": "PostIndexCourseindex",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Index/courseIndex"
      }
    ]
  },
  {
    "type": "post",
    "url": "Index/courseType",
    "title": "精品课程课程分类",
    "version": "0.1.0",
    "group": "Index",
    "description": "<p>精品课程课程分类</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200, \n   \"data\": [\n       {\n           \"id\": 1, \n           \"type\": \"全能社工\", \n           \"fid\": 0, \n           \"has_two\": \"1\",//有二级分类 0 没有     \n           \"has_video\":\"1\",//有视频 1  没有 0\n           \"data\": [\n               {\n                   \"id\": 2, \n                   \"type\": \"基础知识\"\n               }, \n               {\n                   \"id\": 3, \n                   \"type\": \"政策解读\"\n               }, \n               {\n                   \"id\": 4, \n                   \"type\": \"社区实务\"\n               }, \n               {\n                   \"id\": 5, \n                   \"type\": \"社区治理\"\n               }\n           ]\n       }, \n       {\n           \"id\": 6, \n           \"type\": \"领头雁\", \n           \"fid\": 0, \n           \"has_two\": \"1\",\n           \"has_video\":\"1\",//有视频 1  没有 0\n           \"data\": [\n               {\n                   \"id\": 7, \n                   \"type\": \"领导力培训\"\n               }\n           ]\n       }\n   ]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Index.php",
    "groupTitle": "Index",
    "name": "PostIndexCoursetype",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Index/courseType"
      }
    ]
  },
  {
    "type": "post",
    "url": "Index/link",
    "title": "友情链接",
    "version": "0.1.0",
    "group": "Index",
    "description": "<p>友情链接</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "{\n   \"code\":200,\n   \"data\":[\n       {\n           \"name\":\"CLTPHP\",\n           \"url\":\"http://www.cltphp.com/\"\n       },\n       {\n           \"name\":\"CLTPHP内容管理系统\",\n           \"url\":\"http://www.cltphp.com/\"\n       },\n       {\n           \"name\":\"CLTPHP动态\",\n           \"url\":\"http://www.cltphp.com/news-49.html\"\n       },\n       {\n           \"name\":\"关于我们\",\n           \"url\":\"http://cltphp.com/about-8.html\"\n       },\n       {\n           \"name\":\"CLTPHP相关知识\",\n           \"url\":\"http://cltphp.com/news-51.html\"\n       }\n   ]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Index.php",
    "groupTitle": "Index",
    "name": "PostIndexLink",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Index/link"
      }
    ]
  },
  {
    "type": "post",
    "url": "Index/page",
    "title": "单页内容",
    "version": "0.1.0",
    "group": "Index",
    "description": "<p>单页内容</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "column_id",
            "description": "<p>栏目id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\":200,\n   \"data\":{\n       \"id\":3,\n       \"title\":\"关于我们\",\n       \"content\":\"<p style=\"text-align: center;\">哈哈哈</p><p><img src=\"/muke/public/uploads/ueditor/image/20191129/1575010955567804.jpg\"    title=\"1575010955567804.jpg\" alt=\"2.jpg\"/></p>\"\n   }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": 0,\n\n   \"msg\": \"缺少column_id\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "\n{\n\n   \"code\": -2,\n\n   \"msg\": \"column_id错误\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Index.php",
    "groupTitle": "Index",
    "name": "PostIndexPage",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Index/page"
      }
    ]
  },
  {
    "type": "post",
    "url": "Index/pages",
    "title": "广告单页内容",
    "version": "0.1.0",
    "group": "Index",
    "description": "<p>广告单页内容</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>单页id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\":200,\n   \"data\":{\n       \"id\":3,\n       \"title\":\"关于我们\",\n       \"content\":\"<p style=\"text-align: center;\">哈哈哈</p><p><img src=\"/muke/public/uploads/ueditor/image/20191129/1575010955567804.jpg\"    title=\"1575010955567804.jpg\" alt=\"2.jpg\"/></p>\"\n\t\t  \"time\":\"2019-12-23 15:41\"//发布时间   \n\t\t}\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": 0,\n\n   \"msg\": \"缺少id\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "\n{\n\n   \"code\": -2,\n\n   \"msg\": \"id错误\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Index.php",
    "groupTitle": "Index",
    "name": "PostIndexPages",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Index/pages"
      }
    ]
  },
  {
    "type": "post",
    "url": "Index/system",
    "title": "网站基本信息",
    "version": "0.1.0",
    "group": "Index",
    "description": "<p>网站基本信息</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "{\n   \"code\":200,\n   \"data\":{\n       \"name\":\"社区慕课\", //网站名称\n       \"url\":\"http://cltshow.test/\", //网站地址\n       \"title\":\"社区慕课\", //seo标题\n       \"key\":\"社区慕课，社区，教育\", //seo关键词\n       \"des\":\"中国最大的社区教育网站。\", //seo描述\n       \"bah\":\"京ICP备12003892号-11\",//备案号\n       \"copyright\":\"2019\", //版权\n       \"logo\":\"/muke/public/uploads/20191126/7081b0965714d780f26695d630f5866e.jpeg\", //logo\n       \"company\":\"北京XX文化有限公司\" //公司名称\n       \"search\":\"请输入搜索课程关键词\" //搜索栏内容\n   }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Index.php",
    "groupTitle": "Index",
    "name": "PostIndexSystem",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Index/system"
      }
    ]
  },
  {
    "type": "post",
    "url": "Index/upFiles",
    "title": "上传头像",
    "version": "0.1.0",
    "group": "Index",
    "description": "<p>上传头像</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "head_img",
            "description": "<p>上传图片</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\":200,\n   \"msg\",\"图片上传成功\",\n   \"url\":\"/cltphp/public/uploads/20180613/fcb729987d8e9339bd9b2e85c85f3028.jpg\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": 0,\n     \n   \"msg\": \"上传失败，请重试！\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "\n{\n\n   \"code\": -2,\n     \n   \"msg\": \"请选择要上传的图片\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3:",
          "content": "\n{\n\n   \"code\": -3,\n     \n   \"msg\": \"上传图片过大！\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Index.php",
    "groupTitle": "Index",
    "name": "PostIndexUpfiles",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Index/upFiles"
      }
    ]
  },
  {
    "type": "post",
    "url": "Login/code",
    "title": "验证码登录",
    "version": "0.1.0",
    "group": "Login",
    "description": "<p>验证码登录</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>短信验证码</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>返回提示信息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回搜索结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200,\n   \"msg\": \"登录成功\"\n}",
          "type": "json"
        },
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 220,\n   \"msg\": \"登录成功，请设置密码\"\n   \"data\": {\n       \"token\": \"42c5c0950febe50632680d624fc13adb0324479b\"\n\n}",
          "type": "json"
        },
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 230,\n   \"msg\": \"登录成功，请完善信息\"\n   \"data\": {\n       \"token\": \"42c5c0950febe50632680d624fc13adb0324479b\"\n\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1",
          "content": "\n{\n   \"code\": 0,\n   \"msg\": \"手机号不能为空/手机号不正确/验证码不能为空/验证码必须是数字\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2",
          "content": "\n{\n   \"code\": -2,\n   \"msg\": \"该手机号未被注册\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3",
          "content": "\n{\n   \"code\": -3,\n   \"msg\": \"验证码不正确/验证码已过期\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例",
          "content": "\n{\n   \"code\": -40,\n   \"msg\": \"等待管理员审核\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例",
          "content": "\n{\n   \"code\": -50,\n   \"msg\": \"审核未通过\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Login.php",
    "groupTitle": "Login",
    "name": "PostLoginCode",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Login/code"
      }
    ]
  },
  {
    "type": "post",
    "url": "Login/index",
    "title": "密码登录",
    "version": "0.1.0",
    "group": "Login",
    "description": "<p>密码登录</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "mobile",
            "description": "<p>账号或者手机号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>密码</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>返回提示信息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回搜索结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200,\n   \"msg\": \"登录成功\"\n}",
          "type": "json"
        },
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 230,\n   \"msg\": \"登录成功，请完善信息\"\n   \"data\": {\n       \"token\": \"42c5c0950febe50632680d624fc13adb0324479b\"\n   }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1",
          "content": "\n{\n   \"code\": 0,\n   \"msg\": \"账号不能为空/密码不能为空\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2",
          "content": "\n{\n   \"code\": -2,\n   \"msg\": \"账号不存在\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3",
          "content": "\n{\n   \"code\": -3,\n   \"msg\": \"密码错误\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例",
          "content": "\n{\n   \"code\": -40,\n   \"msg\": \"等待管理员审核\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例",
          "content": "\n{\n   \"code\": -50,\n   \"msg\": \"审核未通过\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Login.php",
    "groupTitle": "Login",
    "name": "PostLoginIndex",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Login/index"
      }
    ]
  },
  {
    "type": "post",
    "url": "Login/sendMsg",
    "title": "登录/找回密码获取短信验证码",
    "version": "0.1.0",
    "group": "Login",
    "description": "<p>登录获取短信验证码</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200,\n   \"msg\": \"发送成功\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1",
          "content": "\n{\n   \"code\": 0,\n   \"msg\": \"手机号不能为空/手机号不正确\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3",
          "content": "\n{\n   \"code\": -200,\n   \"msg\": \"mysql error\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4",
          "content": "\n{\n   \"code\": -30,\n   \"msg\": \"阿里云错误提示信息\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Login.php",
    "groupTitle": "Login",
    "name": "PostLoginSendmsg",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Login/sendMsg"
      }
    ]
  },
  {
    "type": "post",
    "url": "Login/setNewPass",
    "title": "找回密码",
    "version": "0.1.0",
    "group": "Login",
    "description": "<p>找回密码</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>短信验证码</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>密码</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "repassword",
            "description": "<p>确认密码</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>返回提示信息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回搜索结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200,\n   \"msg\": \"密码已重置\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1",
          "content": "\n{\n   \"code\": 0,\n   \"msg\": \"手机号不能为空/手机号不正确/验证码不能为空/密码不能为空/密码长度不能小于 12/密码长度不能超过 20/密码必须包含英文与数字/确认密码不能为空/确认密码和密码不一致\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2",
          "content": "\n{\n   \"code\": -2,\n   \"msg\": \"该手机号未被注册\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3",
          "content": "\n{\n   \"code\": -3,\n   \"msg\": \"验证码不正确/验证码已过期\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4",
          "content": "\n{\n   \"code\": -200,\n   \"msg\": \"密码重置失败\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Login.php",
    "groupTitle": "Login",
    "name": "PostLoginSetnewpass",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Login/setNewPass"
      }
    ]
  },
  {
    "type": "post",
    "url": "My/clearHistory",
    "title": "清除历史",
    "version": "0.1.0",
    "group": "My",
    "description": "<p>清除历史</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "{\n   \"code\": 200, \n   \"msg\": \"清除历史成功\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/My.php",
    "groupTitle": "My",
    "name": "PostMyClearhistory",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/My/clearHistory"
      }
    ]
  },
  {
    "type": "post",
    "url": "My/head_img",
    "title": "用户头像",
    "version": "0.1.0",
    "group": "My",
    "description": "<p>用户头像</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\":200,\n   \"avatar\":\"/cltphp/public/uploads/20180613/fcb729987d8e9339bd9b2e85c85f3028.jpg\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/My.php",
    "groupTitle": "My",
    "name": "PostMyHead_img",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/My/head_img"
      }
    ]
  },
  {
    "type": "post",
    "url": "My/integralOverview",
    "title": "积分概览",
    "version": "0.1.0",
    "group": "My",
    "description": "<p>积分概览</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\":200,\n   \"data\":{\n       \"integral_all\":\"28\",//总积分\n       \"rank_all\":2,//总排名\n       \"integral_jidu\":13,//季度积分\n       \"rank_jidu\":1,//季度排名\n       \"grade\":\"专业\",//等级\n       \"leval\":2 //指针指向\n   }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/My.php",
    "groupTitle": "My",
    "name": "PostMyIntegraloverview",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/My/integralOverview"
      }
    ]
  },
  {
    "type": "post",
    "url": "My/myAnswers",
    "title": "我的答题",
    "version": "0.1.0",
    "group": "My",
    "description": "<p>我的答题</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "{\n   \"code\": 200, \n   \"data\": [\n       {\n           \"id\": 3, //答题日志id\n           \"video_id\": 3, //课程id\n           \"is_right\": 1, //是否正确  1正确 2错误\n           \"answer_time\": \"2019-11-24 20:56\", //答题时间\n           \"title\": \"课程2\", //课程名称\n           \"question_title\": \"问题1\", //问题标题\n           \"choice_text\": [\"lallallallalllallalallalallaallalll\",\"选项2222222\",\"选项444\"] //正确答案\n       }, \n       {\n           \"id\": 2, \n           \"video_id\": 3, \n           \"is_right\": 0, \n           \"answer_time\": \"2019-11-24 20:53\", \n           \"title\": \"课程2\", \n           \"question_title\": \"问题3\", \n           \"choice_text\": [\"lallallallalllallalallalallaallalll\",\"选项2222222\",\"选项444\"]\n       }\n   ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/My.php",
    "groupTitle": "My",
    "name": "PostMyMyanswers",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/My/myAnswers"
      }
    ]
  },
  {
    "type": "post",
    "url": "My/myCollection",
    "title": "我的收藏",
    "version": "0.1.0",
    "group": "My",
    "description": "<p>我的收藏</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\":200,\n   \"data\":[\n       {\n           \"id\":4,//课程id\n           \"title\":\"课程3\",//课程标题\n           \"video_img\":\"/mukez/public/uploads/20191121/de6f951f399540b8b1031d3a6b7da6f0.jpg\",//缩略图\n           \"type\":\"政策解读\",//课程类型\n           \"teacher_name\":\"讲师1\",//讲师姓名\n           \"course_type\": \"1\"  //1 视频课程 2 图文课程\n       },\n       {\n           \"id\":2,\n           \"title\":\"课程2\",\n           \"video_img\":\"/mukez/public/uploads/20191119/565109b2bfa066cb3f22d7a5dbd393e9.jpeg\",\n           \"type\":\"社区实务\",\n           \"teacher_name\":\"讲师1\",\n           \"course_type\": \"1\" \n       }\n   ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/My.php",
    "groupTitle": "My",
    "name": "PostMyMycollection",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/My/myCollection"
      }
    ]
  },
  {
    "type": "post",
    "url": "My/myHistory",
    "title": "观看历史",
    "version": "0.1.0",
    "group": "My",
    "description": "<p>观看历史</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "{\n   \"code\":200,\n   \"data\":[\n       {\n           \"id\":5,//课程id\n           \"title\":\"课程5\",//课程标题\n           \"video_img\":\"/mukez/public/uploads/20191121/aafb91729f8f1622384028da6c328eef.jpeg\",//缩略图\n           \"type\":\"政策解读\",//课程类型\n           \"teacher_name\":\"讲师1\",//讲师姓名\n           \"course_type\": \"1\"  //1 视频课程 2 图文课程\n       },\n       {\n           \"id\":4,\n           \"title\":\"课程3\",\n           \"video_img\":\"/mukez/public/uploads/20191121/de6f951f399540b8b1031d3a6b7da6f0.jpg\",\n           \"type\":\"政策解读\",\n           \"teacher_name\":\"讲师1\",\n           \"course_type\": \"1\"  //1 视频课程 2 图文课程\n       },\n       {\n           \"id\":2,\n           \"title\":\"课程2\",\n           \"video_img\":\"/mukez/public/uploads/20191119/565109b2bfa066cb3f22d7a5dbd393e9.jpeg\",\n           \"type\":\"社区实务\",\n           \"teacher_name\":\"讲师1\",\n           \"course_type\": \"1\"  //1 视频课程 2 图文课程\n       }\n   ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/My.php",
    "groupTitle": "My",
    "name": "PostMyMyhistory",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/My/myHistory"
      }
    ]
  },
  {
    "type": "post",
    "url": "My/myIntegral",
    "title": "我的积分",
    "version": "0.1.0",
    "group": "My",
    "description": "<p>我的积分</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "type",
            "description": "<p>积分类型  1登录积分 2答题积分 3课程积分 4考试积分  5系统积分 6作业积分</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例1：登录积分",
          "content": "\n{\n   \"code\": 200, \n   \"data\": [\n       {\n           \"play_time\": \"2019-11-25 18:52\", //登录时间\n           \"integral\": 5 //积分\n       }\n   ]\n}",
          "type": "json"
        },
        {
          "title": "成功返回示例2：答题积分",
          "content": "{\n   \"code\": 200, \n   \"data\": [\n       {   \n           \"video_id\":\"2\",课程id\n           \"question_title\": \"问题3\", //问题\n           \"title\": \"课程2\", //课程标题\n           \"play_time\": \"2019-11-25 18:51\", //答题时间\n           \"integral\": 5 //积分\n           \"is_right\":1//是否正确 1正确 2错误\n       }, \n       {   \n           \"video_id\":\"3\",课程id\n           \"question_title\": \"问题3\",  \n           \"title\": \"课程2\", \n           \"play_time\": \"2019-11-25 18:52\", \n           \"integral\": 5,\n           \"is_right\":1//是否正确 1正确 2错误\n       }\n   ]\n}",
          "type": "json"
        },
        {
          "title": "成功返回示例3：课程积分",
          "content": "\n{\n   \"code\": 200, \n   \"data\": [\n       {   \n           \"video_id\":\"2\",//课程id\n           \"type_id\":\"4\",//二级课程类型id\n           \"type_fid\":\"1\",//一级课程类型id\n           \"title\": \"课程2\", //课程标题\n           \"video_img\": \"/mukez/public/uploads/20191119/565109b2bfa066cb3f22d7a5dbd393e9.jpeg\", //缩略图\n           \"duration\": \"05:59\", //课程时长\n           \"type\": \"社区实务\", //课程类型\n           \"play_time\": \"2019-11-25 18:52\", //播放时间\n           \"integral\": 5 //积分,\n           \"course_type\": \"1\" // 1 视频课程 2 图文课程\n       }, \n       {   \n           \"video_id\":\"3\",课程id\n           \"type_id\":\"4\",//二级课程类型id\n           \"type_fid\":\"1\",//一级课程类型id\n           \"title\": \"课程4\", \n           \"video_img\": \"/mukez/public/uploads/20191121/08c95afb98d4354d6c325757a458baa5.jpeg\", \n           \"duration\": null, \n           \"type\": \"政策解读\", \n           \"play_time\": \"2019-11-25 19:24\", \n           \"integral\": 5,\n           \"course_type\": \"1\" \n       }\n   ]\n}",
          "type": "json"
        },
        {
          "title": "成功返回示例4：考试积分",
          "content": "\n{\n   \"code\":200,\n   \"data\":[\n       {\n           \"test_id\":1, //试卷id\n           \"title\":\"2020年度\", //试卷名称\n           \"time_start\":\"2019-12-19 14:48\", //开始时间\n           \"time_end\":\"2019-12-29 14:48\", //结束时间\n           \"integral\":4 //积分\n       },\n       {\n           \"test_id\":2,\n           \"title\":\"2021年度\",\n           \"time_start\":\"2019-12-19 14:48\",\n           \"time_end\":\"2019-12-29 14:48\",\n           \"integral\":4\n       }\n   ]\n}",
          "type": "json"
        },
        {
          "title": "成功返回示例5：系统积分",
          "content": "\n{\n   \"code\":200,\n   \"data\":[\n       {\n           \"id\":95, //积分表id\n           \"integral\":-5,  //积分\n           \"reason\":\"测试减分\",  //原因\n           \"play_time\":\"2019-12-23 15:42\" //操作时间\n       },\n       {\n           \"id\":94,\n           \"integral\":5,\n           \"reason\":\"测试减分\",\n           \"play_time\":\"2019-12-23 15:41\"\n       },\n       {\n           \"id\":93,\n           \"integral\":5,\n           \"reason\":\"测试\",\n           \"play_time\":\"2019-12-23 15:40\"\n       }\n   ]\n}",
          "type": "json"
        },
        {
          "title": "成功返回示例6：作业积分",
          "content": "\n{\n   \"code\":200,\n   \"data\":[\n       {\n           \"work_id\":6,//作业id\n           \"worktitle\":\"作业标题\",//作业标题\n           \"video_id\":11,//课程id\n           \"video_title\":\"课程标题\",//课程标题\n           \"upload_time\":\"2020-01-03 14:20\",//提交时间\n           \"integral\":1//积分\n       }\n   ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "\n{\n\n   \"code\": 0,\n\n   \"msg\": \"缺少type\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/My.php",
    "groupTitle": "My",
    "name": "PostMyMyintegral",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/My/myIntegral"
      }
    ]
  },
  {
    "type": "post",
    "url": "My/mylearninfo",
    "title": "个人学习信息",
    "version": "0.1.0",
    "group": "My",
    "description": "<p>个人学习信息</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "{\n   \"code\":200,\n   \"data\":{\n       \"id\":1, //用户id\n       \"nickname\":\"chichu\", //用户昵称\n       \"avatar\":\"/mukez/public/uploads/20191126/7081b0965714d780f26695d630f5866e.jpeg\", //头像\n       \"level_name\":\"用户身份\", //用户身份\n       \"learntime\":14.3, //学习时长\n       \"integral\":\"20\", //总积分\n       \"count\":3, //答题数\n       \"rate\":\"100%\" //正确率\n   }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/My.php",
    "groupTitle": "My",
    "name": "PostMyMylearninfo",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/My/mylearninfo"
      }
    ]
  },
  {
    "type": "post",
    "url": "My/myWork",
    "title": "我的作业",
    "version": "0.1.0",
    "group": "My",
    "description": "<p>我的作业</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>1 全部 2已提交  3未提交</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回搜索结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\":200,\n   \"data\":[\n       {\n           \"work_id\":6, //作业id\n           \"worktitle\":\"11111\", //作业标题\n           \"video_id\":11, //课程id\n           \"video_title\":\"4\", //课程标题\n           \"upload_time\":2020-01-03 14:20, //提交时间\n           \"score\":1, //积分\n           \"work_status\":2 //状态 1待审核 2已审核 3未提交\n       },\n       {\n           \"work_id\":7,\n           \"worktitle\":\"ces\",\n           \"video_id\":11,\n           \"video_title\":\"4\",\n           \"upload_time\":2020-01-03 14:20,\n           \"score\":0,\n           \"work_status\":1\n       }\n   ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/My.php",
    "groupTitle": "My",
    "name": "PostMyMywork",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/My/myWork"
      }
    ]
  },
  {
    "type": "post",
    "url": "My/myWorkDetails",
    "title": "我的作业详情",
    "version": "0.1.0",
    "group": "My",
    "description": "<p>我的作业详情</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "work_id",
            "description": "<p>作业id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回搜索结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\":200,\n   \"data\":{\n       \"worktitle\":\"标题\",//作业标题\n       \"workcontent\":\"内容\",//作业内容\n       \"work_status\":2,//状态 1未审核 2已审核\n       \"score\":1, //获得积分\n       \"upload_time\":\"2020-01-03 14:27\" //提交时间\n   }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "{\n\n   \"code\": 0,\n\n   \"msg\": \"缺少work_id/该作业不存在！\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/My.php",
    "groupTitle": "My",
    "name": "PostMyMyworkdetails",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/My/myWorkDetails"
      }
    ]
  },
  {
    "type": "post",
    "url": "My/questions",
    "title": "问题列表",
    "version": "0.1.0",
    "group": "My",
    "description": "<p>问题列表</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "test_id",
            "description": "<p>试卷id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\":200,\n   \"data\":{\n       \"title\":\"2019年度\",//试卷名称\n       \"time_start\":\"2019-12-11 14:48\",//开始时间\n       \"time_end\":\"2019-12-16 14:48\",//结束时间\n       \"end_timeStamp\":1576478930,//结束时间戳\n       \"questions\":[//问题\n           {\n               \"id\":2,//问题id\n               \"question_title\":\"问题1\",//题目\n               \"answer_type\":2,//1单选 2 多选\n               \"option\":[ //选项\n                   {\n                       \"id\":114, //选项id\n                       \"choice_text\":\"选项1\"//选项内容\n                   },\n                   {\n                       \"id\":115,\n                       \"choice_text\":\"选项2\"\n                   },\n                   {\n                       \"id\":116,\n                       \"choice_text\":\"选项3\"\n                   },\n                   {\n                       \"id\":117,\n                       \"choice_text\":\"选项4\"\n                   }\n               ]\n           }\n       ]\n   }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "\n{\n\n   \"code\": -2,\n\n   \"msg\": \"缺少test_id\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3:",
          "content": "\n{\n\n   \"code\": -3,\n\n   \"msg\": \"该试题不存在！/该试题已过期！\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4:",
          "content": "\n{\n\n   \"code\": -4,\n\n   \"msg\": \"无权限\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例5:",
          "content": "\n{\n\n   \"code\": -5,\n\n   \"msg\": \"您已回答过该试题！\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/My.php",
    "groupTitle": "My",
    "name": "PostMyQuestions",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/My/questions"
      }
    ]
  },
  {
    "type": "post",
    "url": "My/rank",
    "title": "榜单",
    "version": "0.1.0",
    "group": "My",
    "description": "<p>榜单</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>榜单类型  1总榜 2季度榜</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例1 总榜",
          "content": "{\n   \"code\":200,\n   \"data\":[\n       {\n           \"id\":19,//用户id\n           \"nickname\":\"11\",//昵称\n           \"avatar\":\"/mukez/public/static/admin/images/0.jpg\",//头像\n           \"integral\":\"28\",//总积分\n           \"grade\":\"专家\" //等级\n       },\n       {\n           \"id\":1,\n           \"nickname\":\"admin\",\n           \"avatar\":null,\n           \"integral\":\"24\",\n           \"grade\":\"专业\"\n       },\n       {\n           \"id\":9,\n           \"nickname\":\"啦啦啦\",\n           \"avatar\":\"/mukez/public/static/admin/images/0.jpg\",\n           \"integral\":\"22\",\n           \"grade\":\"专业\"\n       },\n       {\n           \"id\":4,\n           \"nickname\":\"啦啦啦\",\n           \"avatar\":null,\n           \"integral\":\"14\",\n           \"grade\":\"业余\"\n       },\n       {\n           \"id\":18,\n           \"nickname\":null,\n           \"avatar\":\"/mukez/public/static/admin/images/0.jpg\",\n           \"integral\":\"6\",\n           \"grade\":\"业余\"\n       },\n       {\n           \"id\":17,\n           \"nickname\":null,\n           \"avatar\":\"/mukez/public/static/admin/images/0.jpg\",\n           \"integral\":\"6\",\n           \"grade\":\"业余\"\n       },\n       {\n           \"id\":16,\n           \"nickname\":null,\n           \"avatar\":\"/mukez/public/static/admin/images/0.jpg\",\n           \"integral\":\"6\",\n           \"grade\":\"业余\"\n       },\n       {\n           \"id\":15,\n           \"nickname\":null,\n           \"avatar\":\"/mukez/public/static/admin/images/0.jpg\",\n           \"integral\":\"6\",\n           \"grade\":\"业余\"\n       },\n       {\n           \"id\":14,\n           \"nickname\":null,\n           \"avatar\":\"/mukez/public/static/admin/images/0.jpg\",\n           \"integral\":\"6\",\n           \"grade\":\"业余\"\n       },\n       {\n           \"id\":13,\n           \"nickname\":null,\n           \"avatar\":\"/mukez/public/static/admin/images/0.jpg\",\n           \"integral\":\"6\",\n           \"grade\":\"业余\"\n       }\n   ]\n}",
          "type": "json"
        },
        {
          "title": "成功返回示例2 季度榜",
          "content": "\n{\n   \"code\":200,\n   \"data\":[\n       {\n           \"id\":19,//用户id\n           \"nickname\":\"11\", //昵称\n           \"integral\":13, //积分数\n           \"avatar\":\"/mukez/public/static/admin/images/0.jpg\", //头像\n           \"grade\":\"专业\", //等级 \n       },\n       {\n           \"id\":18,\n           \"nickname\":null,\n           \"integral\":3,\n           \"avatar\":\"/mukez/public/static/admin/images/0.jpg\",\n           \"grade\":\"业余\",\n       }\n   ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/My.php",
    "groupTitle": "My",
    "name": "PostMyRank",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/My/rank"
      }
    ]
  },
  {
    "type": "post",
    "url": "My/testDetails",
    "title": "答题详情",
    "version": "0.1.0",
    "group": "My",
    "description": "<p>答题详情</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "test_id",
            "description": "<p>试卷id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回搜索结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\":200,\n   \"data\":{\n       \"id\":1,//试卷id\n       \"title\":'2019年度考试',//试卷名称\n       \"time_end\":\"2019-12-16 14:48\",//开始时间\n       \"time_start\":\"2019-12-11 14:48\",//结束时间\n       \"questions\":[ //问题\n           {\n               \"id\":1, //问题id\n               \"question_title\":\"问题1问题1\", //问题题目\n               \"answer_type\":2,  //1单选  2多选\n               \"option\":[\n                   {\n                       \"id\":164,  //选项id\n                       \"choice_text\":\"选项1\"  //选项内容\n                       \"is_right\":'1'//正确 0 错误\n                       \"is_xuan\":'1'//选了 0 没选\n                   },\n                   {\n                       \"id\":165,\n                       \"choice_text\":\"选项2\"\n                       \"is_right\":'1'//正确 0 错误\n                       \"is_xuan\":'1'//选了 0 没选\n                   },\n                   {\n                       \"id\":166,\n                       \"choice_text\":\"选项3\"\n                       \"is_right\":'1'//正确 0 错误\n                       \"is_xuan\":'1'//选了 0 没选\n                   },\n                   {\n                       \"id\":167,\n                       \"choice_text\":\"选项4\"\n                       \"is_right\":'1'//正确 0 错误\n                       \"is_xuan\":'1'//选了 0 没选\n                   }\n               ],\n           },\n           {\n               \"id\":2,\n               \"question_title\":\"问题2问题2\",\n               \"answer_type\":2,\n               \"option\":[\n                   {\n                       \"id\":114,\n                       \"choice_text\":\"选项1\"\n                       \"is_right\":'1'//正确 0 错误\n                       \"is_xuan\":'1'//选了 0 没选\n                   },\n                   {\n                       \"id\":115,\n                       \"choice_text\":\"选项2\"\n                       \"is_right\":'1'//正确 0 错误\n                       \"is_xuan\":'1'//选了 0 没选\n                   },\n                   {\n                       \"id\":116,\n                       \"choice_text\":\"选项3\"\n                       \"is_right\":'1'//正确 0 错误\n                       \"is_xuan\":'1'//选了 0 没选\n                   },\n                   {\n                       \"id\":117,\n                       \"choice_text\":\"选项4\"\n                       \"is_right\":'1'//正确 0 错误\n                       \"is_xuan\":'1'//选了 0 没选\n                   }\n               ],\n           }\n       ]\n   }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "\n{\n\n   \"code\": -2,\n\n   \"msg\": \"缺少test_id\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3:",
          "content": "\n{\n\n   \"code\": -3,\n\n   \"msg\": \"该试题不存在！\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/My.php",
    "groupTitle": "My",
    "name": "PostMyTestdetails",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/My/testDetails"
      }
    ]
  },
  {
    "type": "post",
    "url": "My/testList",
    "title": "我的考试",
    "version": "0.1.0",
    "group": "My",
    "description": "<p>我的考试</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>1未回答  2已回答</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例1 未完成",
          "content": "\n{\n   \"code\":200,\n   \"data\":[\n       {\n           \"id\":2,//试卷id\n           \"title\":\"2020年度\",//试卷名称\n           \"time_start\":\"2019-12-19 14:48\",//开始时间\n           \"time_end\":\"2019-12-29 14:48\",//结束时间\n           \"is_end\":\"0\"//0未结束  1已结束 \n       }\n   ]\n}",
          "type": "json"
        },
        {
          "title": "成功返回示例2 已完成",
          "content": "\n{\n   \"code\":200,\n   \"data\":[\n       {\n           \"id\":1,//试卷id\n           \"title\":\"2019年度\",//试卷名称\n           \"time_start\":\"2019-12-19 14:48\",//开始时间\n           \"time_end\":\"2019-12-29 14:48\",//结束时间\n           \"score\":\"10\"//分数\n       }\n   ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/My.php",
    "groupTitle": "My",
    "name": "PostMyTestlist",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/My/testList"
      }
    ]
  },
  {
    "type": "post",
    "url": "My/testTotals",
    "title": "提交试题",
    "version": "0.1.0",
    "group": "My",
    "description": "<p>提交试题</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "test_id",
            "description": "<p>试卷id</p>"
          },
          {
            "group": "Parameter",
            "type": "Array",
            "optional": false,
            "field": "data",
            "description": "<p>二维数组  问题为question  答案字符串为answer</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回搜索结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\":200,\n   \"data\":4 //分数\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1:",
          "content": "\n{\n\n   \"code\": -1,\n\n   \"msg\": \"请登录\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2:",
          "content": "\n{\n\n   \"code\": -2,\n\n   \"msg\": \"缺少test_id\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3:",
          "content": "\n{\n\n   \"code\": -3,\n\n   \"msg\": \"该试题不存在！/该试题已过期！\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4:",
          "content": "\n{\n\n   \"code\": -4,\n\n   \"msg\": \"无权限\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例5:",
          "content": "\n{\n\n   \"code\": -5,\n\n   \"msg\": \"您已回答过该试题！\"\n\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例6:",
          "content": "\n{\n\n   \"code\": -6,\n\n   \"msg\": \"缺少data！\"\n\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/My.php",
    "groupTitle": "My",
    "name": "PostMyTesttotals",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/My/testTotals"
      }
    ]
  },
  {
    "type": "post",
    "url": "Politicsstatus/index",
    "title": "获取政治面貌列表",
    "version": "0.1.0",
    "group": "Politicsstatus",
    "description": "<p>获取政治面貌列表</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回搜索结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200,\n   \"data\": [\n       {\n           \"id\": 1,\n           \"name\": \"中共党员\"\n       },\n       {\n           \"id\": 2,\n           \"name\": \"中共预备党员\"\n       },\n       {\n           \"id\": 3,\n           \"name\": \"共青团员\"\n       },\n       {\n           \"id\": 4,\n           \"name\": \"民革会员\"\n       },\n       {\n           \"id\": 5,\n           \"name\": \"民盟盟员\"\n       },\n       {\n           \"id\": 6,\n           \"name\": \"民建会员\"\n       },\n       {\n           \"id\": 7,\n           \"name\": \"民进会员\"\n       },\n       {\n           \"id\": 8,\n           \"name\": \"农工党党员\"\n       },\n       {\n           \"id\": 9,\n           \"name\": \"致公党党员\"\n       },\n       {\n           \"id\": 10,\n           \"name\": \"九三学社社员\"\n       },\n       {\n           \"id\": 11,\n           \"name\": \"台盟盟员\"\n       },\n       {\n           \"id\": 12,\n           \"name\": \"无党派人士\"\n       },\n       {\n           \"id\": 13,\n           \"name\": \"群众\"\n       }\n   ]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Politicsstatus.php",
    "groupTitle": "Politicsstatus",
    "name": "PostPoliticsstatusIndex",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Politicsstatus/index"
      }
    ]
  },
  {
    "type": "post",
    "url": "Position/index",
    "title": "获取地区信息",
    "version": "0.1.0",
    "group": "Position",
    "description": "<p>地区信息（根据上级地区编码（parent_code_id）获取同一级别的地区信息）</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "parent_code_id",
            "defaultValue": "100000000000",
            "description": "<p>上级地区编码</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          },
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "data",
            "description": "<p>结果集</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200,\n   \"data\": [\n       {\n           \"id\": 1, // id\n           \"code_id\": 110000000000, // 地区编码\n           \"position_name\": \"北京市\", // 地区名称\n           \"parent_code_id\": 100000000000, // 上级地区编码\n           \"level\": 1 // 地区等级\n       },\n       {\n           \"id\": 7488,\n           \"code_id\": 120000000000,\n           \"position_name\": \"天津市\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 13391,\n           \"code_id\": 130000000000,\n           \"position_name\": \"河北省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 69423,\n           \"code_id\": 140000000000,\n           \"position_name\": \"山西省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 100736,\n           \"code_id\": 150000000000,\n           \"position_name\": \"内蒙古自治区\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 116731,\n           \"code_id\": 160000000000,\n           \"position_name\": \"辽宁省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 134797,\n           \"code_id\": 170000000000,\n           \"position_name\": \"吉林省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 147576,\n           \"code_id\": 180000000000,\n           \"position_name\": \"黑龙江省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 163841,\n           \"code_id\": 190000000000,\n           \"position_name\": \"上海市\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 170106,\n           \"code_id\": 200000000000,\n           \"position_name\": \"江苏省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 194101,\n           \"code_id\": 210000000000,\n           \"position_name\": \"浙江省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 226236,\n           \"code_id\": 220000000000,\n           \"position_name\": \"安徽省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 246299,\n           \"code_id\": 230000000000,\n           \"position_name\": \"福建省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 264750,\n           \"code_id\": 240000000000,\n           \"position_name\": \"江西省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 288107,\n           \"code_id\": 250000000000,\n           \"position_name\": \"山东省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 368133,\n           \"code_id\": 260000000000,\n           \"position_name\": \"河南省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 422972,\n           \"code_id\": 270000000000,\n           \"position_name\": \"湖北省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 453459,\n           \"code_id\": 280000000000,\n           \"position_name\": \"湖南省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 485023,\n           \"code_id\": 290000000000,\n           \"position_name\": \"广东省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 512536,\n           \"code_id\": 300000000000,\n           \"position_name\": \"广西壮族自治区\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 530424,\n           \"code_id\": 310000000000,\n           \"position_name\": \"海南省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 533677,\n           \"code_id\": 320000000000,\n           \"position_name\": \"重庆市\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 545955,\n           \"code_id\": 330000000000,\n           \"position_name\": \"四川省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 604401,\n           \"code_id\": 340000000000,\n           \"position_name\": \"贵州省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 623533,\n           \"code_id\": 350000000000,\n           \"position_name\": \"云南省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 639580,\n           \"code_id\": 360000000000,\n           \"position_name\": \"西藏自治区\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 645848,\n           \"code_id\": 370000000000,\n           \"position_name\": \"陕西省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 667825,\n           \"code_id\": 380000000000,\n           \"position_name\": \"甘肃省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 687018,\n           \"code_id\": 390000000000,\n           \"position_name\": \"青海省\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 692210,\n           \"code_id\": 400000000000,\n           \"position_name\": \"宁夏回族自治区\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 695380,\n           \"code_id\": 410000000000,\n           \"position_name\": \"新疆维吾尔自治区\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 712162,\n           \"code_id\": 710000000000,\n           \"position_name\": \"台湾\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 712563,\n           \"code_id\": 810000000000,\n           \"position_name\": \"香港\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       },\n       {\n           \"id\": 712585,\n           \"code_id\": 820000000000,\n           \"position_name\": \"澳门\",\n           \"parent_code_id\": 100000000000,\n           \"level\": 1\n       }\n   ]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Position.php",
    "groupTitle": "Position",
    "name": "PostPositionIndex",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Position/index"
      }
    ]
  },
  {
    "type": "post",
    "url": "Register/index",
    "title": "注册",
    "version": "0.1.0",
    "group": "Register",
    "description": "<p>注册</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>4位短信验证码</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回搜索结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200,\n   \"msg\": \"注册成功\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1",
          "content": "\n{\n   \"code\": 0,\n   \"msg\": \"手机号不能为空/手机号不正确/验证码不能为空/验证码必须是数字\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2",
          "content": "\n{\n   \"code\": -2,\n   \"msg\": \"该手机号已注册\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3",
          "content": "\n{\n   \"code\": -3,\n   \"msg\": \"验证码不正确/验证码已过期\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4",
          "content": "\n{\n   \"code\": -200,\n   \"msg\": \"注册失败\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Register.php",
    "groupTitle": "Register",
    "name": "PostRegisterIndex",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Register/index"
      }
    ]
  },
  {
    "type": "post",
    "url": "Register/isRegister",
    "title": "查询注册功能开启状态",
    "version": "0.1.0",
    "group": "Register",
    "description": "<p>首页</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          },
          {
            "group": "Success 200",
            "type": "Obj",
            "optional": false,
            "field": "data",
            "description": "<p>返回搜索结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200,\n   \"data\": 1 //注册控制开关：1代表可以注册，2代表注册需要审核，3代表关闭注册功能\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Register.php",
    "groupTitle": "Register",
    "name": "PostRegisterIsregister",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Register/isRegister"
      }
    ]
  },
  {
    "type": "post",
    "url": "Register/sendMsg",
    "title": "注册获取短信验证码",
    "version": "0.1.0",
    "group": "Register",
    "description": "<p>注册获取短信验证码</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200,\n   \"msg\": \"发送成功\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1",
          "content": "\n{\n   \"code\": 0,\n   \"msg\": \"手机号不能为空/手机号不正确\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2",
          "content": "\n{\n   \"code\": -2,\n   \"msg\": \"该手机号已注册\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3",
          "content": "\n{\n   \"code\": -200,\n   \"msg\": \"mysql error\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4",
          "content": "\n{\n   \"code\": -30,\n   \"msg\": \"阿里云错误提示信息\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Register.php",
    "groupTitle": "Register",
    "name": "PostRegisterSendmsg",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Register/sendMsg"
      }
    ]
  },
  {
    "type": "post",
    "url": "Users/bindingMobile",
    "title": "绑定手机号",
    "version": "0.1.0",
    "group": "Users",
    "description": "<p>绑定手机号</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>验证码</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200,\n   \"msg\": \"绑定成功\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1",
          "content": "\n{\n   \"code\": -1,\n   \"msg\": \"请登录\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2",
          "content": "\n{\n   \"code\": 0,\n   \"msg\": \"请输入手机号/手机号不正确/请输入验证码\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3",
          "content": "\n{\n   \"code\": -2,\n   \"msg\": \"该手机号已被绑定\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4",
          "content": "\n{\n   \"code\": -3,\n   \"msg\": \"验证码不正确/验证码已过期\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例5",
          "content": "\n{\n   \"code\": -200,\n   \"msg\": \"绑定失败\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Users.php",
    "groupTitle": "Users",
    "name": "PostUsersBindingmobile",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Users/bindingMobile"
      }
    ]
  },
  {
    "type": "post",
    "url": "Users/checkCode",
    "title": "修改密码/原绑定手机号检测手机号和验证码是否正确",
    "version": "0.1.0",
    "group": "Users",
    "description": "<p>修改密码/原绑定手机号检测手机号和验证码是否正确</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>验证码</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200,\n   \"msg\": \"手机号和验证码一致\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1",
          "content": "\n{\n   \"code\": -1,\n   \"msg\": \"请登录\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2",
          "content": "\n{\n   \"code\": 0,\n   \"msg\": \"请输入手机号/手机号不正确/请输入验证码\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3",
          "content": "\n{\n   \"code\": -3,\n   \"msg\": \"验证码不正确/验证码已过期\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Users.php",
    "groupTitle": "Users",
    "name": "PostUsersCheckcode",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Users/checkCode"
      }
    ]
  },
  {
    "type": "post",
    "url": "Users/checkOldPass",
    "title": "检测原密码是否正确",
    "version": "0.1.0",
    "group": "Users",
    "description": "<p>检测原密码是否正确</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>原密码</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回搜索结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200,\n   \"msg\": '密码正确'\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1",
          "content": "\n{\n   \"code\": -1,\n   \"msg\": \"请登录\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2",
          "content": "\n{\n   \"code\": -3,\n   \"msg\": \"原密码错误\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Users.php",
    "groupTitle": "Users",
    "name": "PostUsersCheckoldpass",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Users/checkOldPass"
      }
    ]
  },
  {
    "type": "post",
    "url": "Users/completeInfo",
    "title": "完善信息",
    "version": "0.1.0",
    "group": "Users",
    "description": "<p>完善信息</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "avatar",
            "description": "<p>头像URL</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "nickname",
            "description": "<p>昵称</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "truename",
            "description": "<p>真实姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id_card",
            "description": "<p>身份证号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>邮箱</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "education",
            "description": "<p>最高学历</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "marital",
            "description": "<p>婚育状况</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "province_code_id",
            "description": "<p>省份/直辖市地区编码</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "city_code_id",
            "description": "<p>市区地区编码</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "district_code_id",
            "description": "<p>县/区地区编码</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "street_code_id",
            "description": "<p>街道/乡镇地区编码</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "politics_status",
            "description": "<p>政治面貌id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回搜索结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200,\n   \"msg\": \"设置成功\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1",
          "content": "\n{\n   \"code\": -1,\n   \"msg\": \"请登录\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2",
          "content": "\n{\n   \"code\": 0,\n   \"msg\": \"请填写昵称 /请填写真实姓名 /请填写身份证号 /身份证号不正确 /请选择省/直辖市 /请选择政治面貌\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3",
          "content": "\n{\n   \"code\": -200,\n   \"msg\": \"设置失败\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4",
          "content": "\n{\n   \"code\": -20,\n   \"msg\": \"请设置密码\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例5",
          "content": "\n{\n   \"code\": -40,\n   \"msg\": \"等待管理员审核\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Users.php",
    "groupTitle": "Users",
    "name": "PostUsersCompleteinfo",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Users/completeInfo"
      }
    ]
  },
  {
    "type": "post",
    "url": "Users/info",
    "title": "用户个人信息",
    "version": "0.1.0",
    "group": "Users",
    "description": "<p>用户个人信息</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回搜索结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200,\n   \"msg\": {\n       \"id\": 2, // id\n       \"mobile\": \"15731179306\", // 手机号\n       \"username\": \"whos1234\", // 账号\n       \"nickname\": \"lin\", // 昵称\n       \"truename\": \"吝祥露\", // 姓名\n       \"sex\": 0, // 性别（1男0女）\n       \"id_card\": null, // 身份证号\n       \"avatar\": \"localhost/partyLearn/public/static/admin/images/0.jpg\", // 头像\n       \"email\": \"2272636812\", //邮箱\n       \"email_type\" :\"@qq.com\"  //邮箱后缀\n       \"province_code_id\": null, // 省份/直辖市地区编码\n       \"city_code_id\": null, // 市区地区编码\n       \"district_code_id\": null, // 县/区地区编码\n       \"street_code_id\": null, // 街道/乡镇地区编码\n       \"politics_status\": null, // 政治面貌id\n       \"province\": null, // 省份/直辖市地区名称\n       \"city\": null, // 市区地区名称\n       \"district\": null, // 县/区地区名称\n       \"street\": null // 街道/乡镇地区名称\n   }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例",
          "content": "\n{\n   \"code\": -1,\n   \"msg\": \"请登录\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例",
          "content": "\n{\n   \"code\": -20,\n   \"msg\": \"请设置密码\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例",
          "content": "\n{\n   \"code\": -30,\n   \"msg\": \"请完善信息\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Users.php",
    "groupTitle": "Users",
    "name": "PostUsersInfo",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Users/info"
      }
    ]
  },
  {
    "type": "post",
    "url": "Users/logOut",
    "title": "退出登录",
    "version": "0.1.0",
    "group": "Users",
    "description": "<p>退出登录</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>返回提示信息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回搜索结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200,\n   \"msg\": \"退出登录成功\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1",
          "content": "\n{\n   \"code\": -1,\n   \"msg\": \"请登录\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2",
          "content": "\n{\n   \"code\": -200,\n   \"msg\": \"退出登录失败\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Users.php",
    "groupTitle": "Users",
    "name": "PostUsersLogout",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Users/logOut"
      }
    ]
  },
  {
    "type": "post",
    "url": "Users/newmSendMsg",
    "title": "新绑定手机号获取短信验证码",
    "version": "0.1.0",
    "group": "Users",
    "description": "<p>新绑定手机号获取短信验证码</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200,\n   \"msg\": \"发送成功\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1",
          "content": "\n{\n   \"code\": -1,\n   \"msg\": \"请登录\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2",
          "content": "\n{\n   \"code\": 0,\n   \"msg\": \"手机号不能为空/手机号不正确\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3",
          "content": "\n{\n   \"code\": -2,\n   \"msg\": \"该手机号已被绑定\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4",
          "content": "\n{\n   \"code\": -200,\n   \"msg\": \"mysql error\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例5",
          "content": "\n{\n   \"code\": -30,\n   \"msg\": \"阿里云错误提示信息\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Users.php",
    "groupTitle": "Users",
    "name": "PostUsersNewmsendmsg",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Users/newmSendMsg"
      }
    ]
  },
  {
    "type": "post",
    "url": "Users/sendMsg",
    "title": "修改密码/原绑定手机号获取短信验证码",
    "version": "0.1.0",
    "group": "Users",
    "description": "<p>修改密码/原绑定手机号获取短信验证码</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200,\n   \"msg\": \"发送成功\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1",
          "content": "\n{\n   \"code\": -1,\n   \"msg\": \"请登录\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2",
          "content": "\n{\n   \"code\": 0,\n   \"msg\": \"手机号不能为空/手机号不正确\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3",
          "content": "\n{\n   \"code\": -200,\n   \"msg\": \"mysql error\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4",
          "content": "\n{\n   \"code\": -30,\n   \"msg\": \"阿里云错误提示信息\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Users.php",
    "groupTitle": "Users",
    "name": "PostUsersSendmsg",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Users/sendMsg"
      }
    ]
  },
  {
    "type": "post",
    "url": "Users/setPass",
    "title": "设置密码",
    "version": "0.1.0",
    "group": "Users",
    "description": "<p>设置密码</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>登录令牌</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>新密码</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "repassword",
            "description": "<p>重复新密码</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>返回搜索结果模型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功返回示例",
          "content": "\n{\n   \"code\": 200,\n   \"msg\": '设置成功'\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败返回示例1",
          "content": "\n{\n   \"code\": -1,\n   \"msg\": \"请登录\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例2",
          "content": "\n{\n   \"code\": 0,\n   \"msg\": \"请输入新密码/密码必须为12-20位字符，且由英文和数字组成/请输入重复新密码/重复密码和密码不一致\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例3",
          "content": "\n{\n   \"code\": -200,\n   \"msg\": \"设置失败\"\n}",
          "type": "json"
        },
        {
          "title": "失败返回示例4",
          "content": "\n{\n   \"code\": -30,\n   \"msg\": \"请完善信息\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Users.php",
    "groupTitle": "Users",
    "name": "PostUsersSetpass",
    "sampleRequest": [
      {
        "url": "https://api.htlocalservice.com/muke/public/api/Users/setPass"
      }
    ]
  }
] });
