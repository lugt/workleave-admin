SUBMENU_CONFIG = {
    "custom": {
        "id": "custom",
        "name": "常用",
        "icon": "",
        "tip": "",
        "parent": "root",
        "top": "",
        "items": {
            "custom_set": {
                "id": "admin_founder",
                "name": "普通请假",
                "icon": "",
                "tip": "",
                "parent": "leave",
                "top": "",
                "url": "index.php?goto=leave"
            },
            "config_site": {
                "id": "config_site",
                "name": "审核请假",
                "icon": "",
                "tip": "",
                "parent": "config",
                "top": "",
                "url": "index.php?goto=auth"
            },
            "bbs_article": {
                "id": "bbs_article",
                "name": "现有请假",
                "icon": "",
                "tip": "",
                "parent": "contents",
                "top": "",
                "url": "index.php?goto=elder"
            }
        }
    },
    "leave": {
        "id": "leave",
        "name": "请假",
        "icon": "",
        "tip": "",
        "parent": "root",
        "top": "",
        "items": {
            "admin_founder": {
                "id": "admin_founder",
                "name": "普通请假",
                "icon": "",
                "tip": "",
                "parent": "leave",
                "top": "",
                "url": "index.php?goto=leave"
            }
        }
    },
    "config": {
        "id": "config",
        "name": "审核请假",
        "icon": "",
        "tip": "",
        "parent": "root",
        "top": "",
        "items": {
            "config_site": {
                "id": "config_site",
                "name": "审核请假",
                "icon": "",
                "tip": "",
                "parent": "config",
                "top": "",
                "url": "index.php?goto=auth"
            },
            
            "config_all": {
                "id": "config_all",
                "name": "批量审核",
                "icon": "",
                "tip": "",
                "parent": "config",
                "top": "",
                "url": "index.php?goto=multiauth"
            }
        }
    },
    "u": {
        "id": "u",
        "name": "账户",
        "icon": "",
        "tip": "",
        "parent": "root",
        "top": "",
        "items": {
            "u_groups": {
                "id": "u_groups",
                "name": "我的信息",
                "icon": "",
                "tip": "",
                "parent": "u",
                "top": "",
                "url": "index.php?cast=me"
            },
             "u_cgp": {
                "id": "u_cgp",
                "name": "更改密码",
                "icon": "",
                "tip": "",
                "parent": "u",
                "top": "",
                "url": "auth/cgp.php"
            }
        }
    },
    "contents": {
        "id": "contents",
        "name": "记录",
        "icon": "",
        "tip": "",
        "parent": "root",
        "top": "",
        "items": {
            "bbs_article": {
                "id": "bbs_article",
                "name": "往期请假",
                "icon": "",
                "tip": "",
                "parent": "contents",
                "top": "",
                "url": "index.php?goto=elder"
            }
        }
    },
    "design": {
        "id": "design",
        "name": "管理",
        "icon": "",
        "tip": "",
        "parent": "root",
        "top": "",
        "items": {
            "refresher": {
                "id": "refresher",
                "name": "更新教师数据",
                "icon": "",
                "tip": "",
                "parent": "design",
                "top": "",
                "url": "index.php?cast=refresh"
            },
            
            "design_page": {
                "id": "design_page",
                "name": "用户注册",
                "icon": "",
                "tip": "",
                "parent": "design",
                "top": "",
                "url": "auth/reg.php"
            }
        }
    }
};