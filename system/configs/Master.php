<?php 

$configs["developer"] = array(
	"account" => "develop",
	"password" => "phc"
);


//////////////////////////////////////////////////////////////////
//                            網站設定
//////////////////////////////////////////////////////////////////

$information_metas["base_information"] = array(
	"name" => "基本設定",
	"variable" => "base_information",
	"type" => "grouplabel",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$information_metas["title"] = array(
	"name" => "系統名稱",
	"variable" => "title",
	"type" => "text",
	"properties" => array("max" => 30),
	"xss" => true,
	"tip" => "系統管理平台會出現的名字",
	"default" => "",
	"list" => "Y"
);

$information_metas["domain"] = array(
	"name" => "是否多網域",
	"variable" => "domain",
	"type" => "toggle",
	"properties" => array("data_source" => "是,否"),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "N"
);


$information_metas["google_information"] = array(
	"name" => "Google登入",
	"variable" => "google_information",
	"type" => "grouplabel",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$information_metas["google"] = array(
	"name" => "Google登入開關",
	"variable" => "google",
	"type" => "toggle",
	"properties" => array("data_source" => "是,否"),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$information_metas["google_app_id"] = array(
	"name" => "Google APP ID",
	"variable" => "google_app_id",
	"type" => "text",
	"properties" => array("max" => 50),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$information_metas["google_secret"] = array(
	"name" => "Google Secret",
	"variable" => "google_secret",
	"type" => "text",
	"properties" => array("max" => 50),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$information_metas["facebook_information"] = array(
	"name" => "Facebook登入",
	"variable" => "facebook_information",
	"type" => "grouplabel",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$information_metas["facebook"] = array(
	"name" => "Facebook登入開關",
	"variable" => "facebook",
	"type" => "toggle",
	"properties" => array("data_source" => "是,否"),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$information_metas["facebook_app_id"] = array(
	"name" => "Facebook APP ID",
	"variable" => "facebook_app_id",
	"type" => "text",
	"properties" => array("max" => 50),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$information_metas["facebook_secret"] = array(
	"name" => "Facebook Secret",
	"variable" => "facebook_secret",
	"type" => "text",
	"properties" => array("max" => 50),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$configs["master_information"] = array(
	"title" => "網站設定",
	"model" => "master-form",
	"data_source" => "sys_information",
	"item_control" => "ADD,FIX,DEL",
	"item_fields" => $information_metas
);



//////////////////////////////////////////////////////////////////
//                            零件管理
//////////////////////////////////////////////////////////////////



$widget_metas["base_information"] = array(
	"name" => "基本設定",
	"variable" => "base_information",
	"type" => "grouplabel",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$widget_metas["title"] = array(
	"name" => "零件名稱",
	"variable" => "title",
	"type" => "text",
	"properties" => array("max" => 30),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);

$widget_metas["code"] = array(
	"name" => "引用代碼",
	"variable" => "code",
	"type" => "text",
	"properties" => array("max" => 30),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);

$widget_metas["model"] = array(
	"name" => "零件模組",
	"variable" => "model",
	"type" => "widgetlist",
	"properties" => array("max" => 30),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);

$widget_metas["data_source"] = array(
	"name" => "資料來源",
	"variable" => "data_source",
	"type" => "text",
	"properties" => array("max" => 30),
	"xss" => true,
	"tip" => "資料表名稱或自制Model名稱，不需加前輟詞(e.g., web_)",
	"default" => "",
	"list" => "Y"
);

/************************************************
 *                    分類設定
 ************************************************/

$widget_metas["class_information"] = array(
	"name" => "分類設定",
	"variable" => "class_information",
	"type" => "grouplabel",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
	
);

$widget_metas["class_level"] = array(
	"name" => "分類層數",
	"variable" => "class_level",
	"type" => "number",
	"properties" => array("type" => "integer"),
	"xss" => true,
	"tip" => "此資料可分為幾層分類",
	"default" => "0",
	"list" => "N"
);


$widget_metas["dynamic_level"] = array(
	"name" => "動態層數",
	"variable" => "dynamic_level",
	"type" => "toggle",
	"properties" => array("data_source" => "是,否"),
	"xss" => true,
	"tip" => "選擇「是」，則不限定最後一層才可放項目",
	"default" => "",
	"list" => "N"
);

$widget_metas["class_count"] = array(
	"name" => "每頁筆數",
	"variable" => "class_count",
	"type" => "number",
	"properties" => array("type" => "integer"),
	"xss" => true,
	"tip" => "",
	"default" => "10",
	"list" => "N"
);

$widget_metas["class_control"] = array(
	"name" => "權限控制",
	"variable" => "class_control",
	"type" => "checkbox",
	"properties" => array("data_source" => "ADD:新增,FIX:修改,DEL:刪除,VIEW:檢視,CLONE:複製,SORT:排序,PUBLISH:上下架,EXPORT:匯出,PRINT:列印"),
	"xss" => true,
	"tip" => "",
	"default" => "ADD,FIX,DEL,SORT,PUBLISH",
	"list" => "N"
);

$widget_metas["class_fields"] = array(
	"name" => "欄位設定",
	"variable" => "class_fields",
	"type" => "fieldset",
	"properties" => array("fields" => array(

			array(
				"name" => "欄位名稱",
				"variable" => "name",
				"type" => "text",
				"properties" => array("max" => 30),
				"xss" => true,
			),

			array(
				"name" => "欄位變數",
				"variable" => "variable",
				"type" => "text",
				"properties" => array(),
				"xss" => true,
			),

			array(
				"name" => "元件",
				"variable" => "type",
				"type" => "componentlist",
				"properties" => array(),
				"xss" => true,
			),

			array(
				"name" => "控制元素",
				"variable" => "properties",
				"type" => "text",
				"properties" => array(),
				"xss" => false,
			),

			array(
				"name" => "預設值",
				"variable" => "default",
				"type" => "text",
				"properties" => array(),
				"xss" => true,
			),

			array(
				"name" => "提示說明",
				"variable" => "tip",
				"type" => "text",
				"properties" => array(),
				"xss" => false,
			),

			array(
				"name" => "XSS",
				"variable" => "xss",
				"type" => "select",
				"properties" => array("data_source" => "N:否,Y:是"),
				"xss" => true,
			),

			array(
				"name" => "是否顯示清單",
				"variable" => "list",
				"type" => "select",
				"properties" => array("data_source" => "N:否,Y:是"),
				"xss" => true,
			),

		)

	),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);


/************************************************
 *                    特殊分類設定
 ************************************************/


$widget_metas["special_class_information"] = array(
	"name" => "特殊分類設定",
	"variable" => "special_class_information",
	"type" => "grouplabel",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);


$widget_metas["special_class_level"] = array(
	"name" => "所在分類層數",
	"variable" => "special_class_level",
	"type" => "number",
	"properties" => array("type" => "integer"),
	"xss" => true,
	"tip" => "在此指定的層數可使用自訂欄位",
	"default" => "0",
	"list" => "N"
);

$widget_metas["special_class_control"] = array(
	"name" => "權限控制",
	"variable" => "special_class_control",
	"type" => "checkbox",
	"properties" => array("data_source" => "ADD:新增,FIX:修改,DEL:刪除,VIEW:檢視,CLONE:複製,SORT:排序,PUBLISH:上下架,EXPORT:匯出,PRINT:列印"),
	"xss" => true,
	"tip" => "",
	"default" => "ADD,FIX,DEL,SORT,PUBLISH",
	"list" => "N"
);

$widget_metas["special_class_fields"] = array(
	"name" => "欄位設定",
	"variable" => "special_class_fields",
	"type" => "fieldset",
	"properties" => array("fields" => array(

			array(
				"name" => "欄位名稱",
				"variable" => "name",
				"type" => "text",
				"properties" => array("max" => 30),
				"xss" => true,
			),

			array(
				"name" => "欄位變數",
				"variable" => "variable",
				"type" => "text",
				"properties" => array(),
				"xss" => true,
			),

			array(
				"name" => "元件",
				"variable" => "type",
				"type" => "componentlist",
				"properties" => array(),
				"xss" => true,
			),

			array(
				"name" => "控制元素",
				"variable" => "properties",
				"type" => "text",
				"properties" => array(),
				"xss" => false,
			),

			array(
				"name" => "預設值",
				"variable" => "default",
				"type" => "text",
				"properties" => array(),
				"xss" => true,
			),


			array(
				"name" => "提示說明",
				"variable" => "tip",
				"type" => "text",
				"properties" => array(),
				"xss" => false,
			),

			array(
				"name" => "XSS",
				"variable" => "xss",
				"type" => "select",
				"properties" => array("data_source" => "N:否,Y:是"),
				"xss" => true,
			),

			array(
				"name" => "是否顯示清單",
				"variable" => "list",
				"type" => "select",
				"properties" => array("data_source" => "N:否,Y:是"),
				"xss" => true,
			),
		)
	),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);


/************************************************
 *                    項目設定
 ************************************************/

$widget_metas["item_information"] = array(
	"name" => "項目設定",
	"variable" => "item_information",
	"type" => "grouplabel",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$widget_metas["item_max_count"] = array(
	"name" => "項目數量限制",
	"variable" => "item_max_count",
	"type" => "number",
	"properties" => array("type" => "integer"),
	"xss" => true,
	"tip" => "每個分類最多可新增幾個項目(0：無限, -1:不需要項目)",
	"default" => "0",
	"list" => "N"
);

$widget_metas["item_count"] = array(
	"name" => "每頁筆數",
	"variable" => "item_count",
	"type" => "number",
	"properties" => array("type" => "integer"),
	"xss" => true,
	"tip" => "",
	"default" => "10",
	"list" => "N"
);

$widget_metas["item_control"] = array(
	"name" => "權限控制",
	"variable" => "item_control",
	"type" => "checkbox",
	"properties" => array("data_source" => "ADD:新增,FIX:修改,DEL:刪除,VIEW:檢視,CLONE:複製,SORT:排序,PUBLISH:上下架,EXPORT:匯出,PRINT:列印"),
	"xss" => true,
	"tip" => "",
	"default" => "ADD,FIX,DEL,SORT,PUBLISH",
	"list" => "N"
);

$widget_metas["item_fields"] = array(
	"name" => "欄位設定",
	"variable" => "item_fields",
	"type" => "fieldset",
	"properties" => array("fields" => array(

			array(
				"name" => "欄位名稱",
				"variable" => "name",
				"type" => "text",
				"properties" => array("max" => 30),
				"xss" => true,
			),

			array(
				"name" => "欄位變數",
				"variable" => "variable",
				"type" => "text",
				"properties" => array(),
				"xss" => true,
			),

			array(
				"name" => "元件",
				"variable" => "type",
				"type" => "componentlist",
				"properties" => array(),
				"xss" => true,
			),

			array(
				"name" => "控制元素",
				"variable" => "properties",
				"type" => "text",
				"properties" => array(),
				"xss" => false,
			),

			array(
				"name" => "預設值",
				"variable" => "default",
				"type" => "text",
				"properties" => array(),
				"xss" => true,
			),


			array(
				"name" => "提示說明",
				"variable" => "tip",
				"type" => "text",
				"properties" => array(),
				"xss" => false,
			),

			array(
				"name" => "XSS",
				"variable" => "xss",
				"type" => "select",
				"properties" => array("data_source" => "N:否,Y:是"),
				"default" => "Y",
				"xss" => true,
			),

			array(
				"name" => "是否顯示清單",
				"variable" => "list",
				"type" => "select",
				"properties" => array("data_source" => "N:否,Y:是"),
				"xss" => true,
			),

		)

	),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);






$configs["master_widget"] = array(
	"title" => "零件管理",
	"model" => "master-data",
	"data_source" => "sys_widget",
	"item_control" => "ADD,FIX,DEL",
	"item_fields" => $widget_metas
);


//////////////////////////////////////////////////////////////////
//                            會員管理
//////////////////////////////////////////////////////////////////



$member_metas["base_information"] = array(
	"name" => "基本設定",
	"variable" => "base_information",
	"type" => "grouplabel",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$member_metas["title"] = array(
	"name" => "零件名稱",
	"variable" => "title",
	"type" => "text",
	"properties" => array("max" => 30),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);

$member_metas["code"] = array(
	"name" => "引用代碼",
	"variable" => "code",
	"type" => "text",
	"properties" => array("max" => 30),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);

$member_metas["model"] = array(
	"name" => "零件模組",
	"variable" => "model",
	"type" => "widgetlist",
	"properties" => array("max" => 30),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);

$member_metas["data_source"] = array(
	"name" => "資料來源",
	"variable" => "data_source",
	"type" => "text",
	"properties" => array("max" => 30),
	"xss" => true,
	"tip" => "資料表名稱或自制Model名稱，不需加前輟詞(e.g., web_)",
	"default" => "",
	"list" => "Y"
);

$member_metas["admin_login"] = array(
	"name" => "可登入後台",
	"variable" => "admin_login",
	"type" => "toggle",
	"properties" => array("data_source" => "是,否"),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);

$member_metas["authority"] = array(
	"name" => "權限設定",
	"variable" => "authority",
	"type" => "authoritylist",
	"properties" => array("max" => 30),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);


/************************************************
 *                    項目設定
 ************************************************/

$member_metas["item_information"] = array(
	"name" => "項目設定",
	"variable" => "item_information",
	"type" => "grouplabel",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$member_metas["item_max_count"] = array(
	"name" => "項目數量限制",
	"variable" => "item_max_count",
	"type" => "number",
	"properties" => array("type" => "integer"),
	"xss" => true,
	"tip" => "每個分類最多可新增幾個項目(0：無限, -1:不需要項目)",
	"default" => "0",
	"list" => "N"
);

$member_metas["item_count"] = array(
	"name" => "每頁筆數",
	"variable" => "item_count",
	"type" => "number",
	"properties" => array("type" => "integer"),
	"xss" => true,
	"tip" => "",
	"default" => "10",
	"list" => "N"
);

$member_metas["item_control"] = array(
	"name" => "權限控制",
	"variable" => "item_control",
	"type" => "checkbox",
	"properties" => array("data_source" => "ADD:新增,FIX:修改,DEL:刪除,VIEW:檢視,CLONE:複製,SORT:排序,PUBLISH:上下架,EXPORT:匯出,PRINT:列印"),
	"xss" => true,
	"tip" => "",
	"default" => "ADD,FIX,DEL,SORT,PUBLISH",
	"list" => "N"
);

$member_metas["item_fields"] = array(
	"name" => "欄位設定",
	"variable" => "item_fields",
	"type" => "fieldset",
	"properties" => array("fields" => array(

			array(
				"name" => "欄位名稱",
				"variable" => "name",
				"type" => "text",
				"properties" => array("max" => 30),
				"xss" => true,
			),

			array(
				"name" => "欄位變數",
				"variable" => "variable",
				"type" => "text",
				"properties" => array(),
				"xss" => true,
			),

			array(
				"name" => "元件",
				"variable" => "type",
				"type" => "componentlist",
				"properties" => array(),
				"xss" => true,
			),

			array(
				"name" => "控制元素",
				"variable" => "properties",
				"type" => "text",
				"properties" => array(),
				"xss" => false,
			),

			array(
				"name" => "預設值",
				"variable" => "default",
				"type" => "text",
				"properties" => array(),
				"xss" => true,
			),


			array(
				"name" => "提示說明",
				"variable" => "tip",
				"type" => "text",
				"properties" => array(),
				"xss" => false,
			),

			array(
				"name" => "XSS",
				"variable" => "xss",
				"type" => "select",
				"properties" => array("data_source" => "N:否,Y:是"),
				"default" => "Y",
				"xss" => true,
			),

			array(
				"name" => "是否顯示清單",
				"variable" => "list",
				"type" => "select",
				"properties" => array("data_source" => "N:否,Y:是"),
				"xss" => true,
			),

		)

	),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$member_metas["search_information"] = array(
	"name" => "搜尋設定",
	"variable" => "search_information",
	"type" => "grouplabel",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$member_metas["search_fields"] = array(
	"name" => "搜尋項目",
	"variable" => "search_fields",
	"type" => "fieldset",
	"properties" => array("fields" => array(

			array(
				"name" => "欄位名稱",
				"variable" => "name",
				"type" => "text",
				"properties" => array("max" => 30),
				"xss" => true,
			),

			array(
				"name" => "欄位變數",
				"variable" => "variable",
				"type" => "text",
				"properties" => array(),
				"xss" => true,
			),

			array(
				"name" => "元件",
				"variable" => "type",
				"type" => "componentlist",
				"properties" => array(),
				"xss" => true,
			),

			array(
				"name" => "控制元素",
				"variable" => "properties",
				"type" => "text",
				"properties" => array(),
				"xss" => false,
			),

			array(
				"name" => "SQL語法(WHERE)",
				"variable" => "sql",
				"type" => "text",
				"properties" => array(),
				"xss" => true,
			)
		)

	),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);




$configs["master_member"] = array(
	"title" => "會員管理",
	"model" => "master-data",
	"data_source" => "sys_member",
	"item_control" => "ADD,FIX,DEL",
	"item_fields" => $member_metas
);



//////////////////////////////////////////////////////////////////
//                            頁面名稱
//////////////////////////////////////////////////////////////////


$page_class_metas["title"] = array(
	"name" => "頁面名稱",
	"variable" => "title",
	"type" => "text",
	"properties" => array("max" => 30),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);

$page_class_metas["landing"] = array(
	"name" => "登入頁",
	"variable" => "landing",
	"type" => "toggle",
	"properties" => array("data_source" => "是,否"),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);

$page_class_metas["logout"] = array(
	"name" => "登出頁",
	"variable" => "logout",
	"type" => "toggle",
	"properties" => array("data_source" => "是,否"),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$page_metas["widgetID"] = array(
	"name" => "零件模組",
	"variable" => "widgetID",
	"type" => "widgets",
	"properties" => array("max" => 30),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);

$page_metas["hide"] = array(
	"name" => "是否隱藏",
	"variable" => "hide",
	"type" => "toggle",
	"properties" => array("data_source" => "是,否"),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);

$page_metas["desktop"] = array(
	"name" => "桌機比例",
	"variable" => "desktop",
	"type" => "number",
	"properties" => array("type" => "integer"),
	"xss" => true,
	"tip" => "請輸入1 ~ 12",
	"default" => "",
	"list" => "Y"
);

$page_metas["pad"] = array(
	"name" => "平板比例",
	"variable" => "pad",
	"type" => "number",
	"properties" => array("type" => "integer"),
	"xss" => true,
	"tip" => "請輸入1 ~ 12",
	"default" => "",
	"list" => "Y"
);

$page_metas["phone"] = array(
	"name" => "手機比例",
	"variable" => "phone",
	"type" => "number",
	"properties" => array("type" => "integer"),
	"xss" => true,
	"tip" => "請輸入1 ~ 12",
	"default" => "",
	"list" => "Y"
);


$configs["master_page"] = array(
	"title" => "頁面管理",
	"model" => "master-data",
	"data_source" => "sys_page",
	"class_level" => 1,
	"class_fields" => $page_class_metas,
	"item_control" => "ADD,FIX,DEL,SORT",
	"item_fields" => $page_metas
);

//////////////////////////////////////////////////////////////////
//                            選單管理
//////////////////////////////////////////////////////////////////


$menu_metas["menu"] = array(
	"name" => "系統名稱",
	"variable" => "menu",
	"type" => "menu",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);


$configs["master_menu"] = array(
	"title" => "選單管理",
	"model" => "master-form",
	"data_source" => "sys_menu",
	"item_control" => "ADD,FIX,DEL",
	"item_fields" => $menu_metas
);


//////////////////////////////////////////////////////////////////
//                            信箱設定
//////////////////////////////////////////////////////////////////

$mail_metas["baseInformation"] = array(
	"name" => "基本資訊",
	"variable" => "baseInformation",
	"type" => "grouplabel",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$mail_metas["code"] = array(
	"name" => "引用代碼",
	"variable" => "code",
	"type" => "text",
	"properties" => array("max" => 50),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);

$mail_metas["mailInformation"] = array(
	"name" => "信箱資訊",
	"variable" => "mailInformation",
	"type" => "grouplabel",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$mail_metas["sendMail"] = array(
	"name" => "發信信箱",
	"variable" => "sendMail",
	"type" => "email",
	"properties" => array("max" => 50),
	"xss" => true,
	"tip" => "發送信件的信箱位址",
	"default" => "",
	"list" => "Y"
);

$mail_metas["replyMail"] = array(
	"name" => "回覆信箱",
	"variable" => "replyMail",
	"type" => "email",
	"properties" => array("max" => 50),
	"xss" => true,
	"tip" => "郵件系統回覆時預設信箱",
	"default" => "",
	"list" => "Y"
);

$mail_metas["sendName"] = array(
	"name" => "信箱名稱",
	"variable" => "sendName",
	"type" => "email",
	"properties" => array("max" => 50),
	"xss" => true,
	"tip" => "寄件者名稱",
	"default" => "",
	"list" => "Y"
);

$mail_metas["smtpInformation"] = array(
	"name" => "SMTP伺服器資訊",
	"variable" => "smtpInformation",
	"type" => "grouplabel",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$mail_metas["smtpGate"] = array(
	"name" => "啟用SMTP",
	"variable" => "smtpGate",
	"type" => "toggle",
	"properties" => array("data_source" => "是,否"),
	"xss" => true,
	"tip" => "是否指定由SMTP伺服器發信",
	"default" => "",
	"list" => "Y"
);

$mail_metas["smtpHost"] = array(
	"name" => "SMTP主機",
	"variable" => "smtpHost",
	"type" => "text",
	"properties" => array("max" => "50"),
	"xss" => true,
	"tip" => "請輸入SMTP伺服器網址",
	"default" => "",
	"list" => "N"
);

$mail_metas["smtpPort"] = array(
	"name" => "SMTP伺服器連接埠",
	"variable" => "smtpPort",
	"type" => "number",
	"properties" => array("type" => "integer"),
	"xss" => true,
	"tip" => "請輸入SMTP伺服器連接埠",
	"default" => "",
	"list" => "N"
);

$mail_metas["smtpUser"] = array(
	"name" => "SMTP帳號",
	"variable" => "smtpUser",
	"type" => "text",
	"properties" => array("max" => "50"),
	"xss" => true,
	"tip" => "請輸入SMTP伺服器登入帳號",
	"default" => "",
	"list" => "N"
);

$mail_metas["smtpPassword"] = array(
	"name" => "SMTP密碼",
	"variable" => "smtpPassword",
	"type" => "text",
	"properties" => array("max" => "50"),
	"xss" => true,
	"tip" => "請輸入SMTP伺服器登入密碼",
	"default" => "",
	"list" => "N"
);

$mail_metas["smtpVerify"] = array(
	"name" => "SMTP驗證",
	"variable" => "smtpVerify",
	"type" => "toggle",
	"properties" => array("data_source" => "是,否"),
	"xss" => true,
	"tip" => "SMTP伺服器是否需要驗證",
	"default" => "",
	"list" => "N"
);

$mail_metas["smtpSecure"] = array(
	"name" => "SMTP安全協定",
	"variable" => "smtpSecure",
	"type" => "select",
	"properties" => array("data_source" => "無:,ssl:SSL,tls:TLS"),
	"xss" => true,
	"tip" => "指定SMTP伺服器的安全協定",
	"default" => "",
	"list" => "N"
);


$configs["master_mailsetting"] = array(
	"title" => "信箱設定",
	"model" => "master-data",
	"data_source" => "sys_mail_setting",
	"class_level" => 0,
	"item_control" => "ADD,FIX,DEL",
	"item_fields" => $mail_metas
);


//////////////////////////////////////////////////////////////////
//                            信件樣版
//////////////////////////////////////////////////////////////////

$mailtemplate_metas["baseInformation"] = array(
	"name" => "基本資訊",
	"variable" => "baseInformation",
	"type" => "grouplabel",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$mailtemplate_metas["code"] = array(
	"name" => "引用代碼",
	"variable" => "code",
	"type" => "text",
	"properties" => array("max" => 50),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);

$mailtemplate_metas["mailInformation"] = array(
	"name" => "信件資訊",
	"variable" => "mailInformation",
	"type" => "grouplabel",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$mailtemplate_metas["subject"] = array(
	"name" => "主旨",
	"variable" => "subject",
	"type" => "text",
	"properties" => array("max" => 100),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);

$mailtemplate_metas["content"] = array(
	"name" => "信件內容",
	"variable" => "content",
	"type" => "html",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);




$configs["master_mailtemplate"] = array(
	"title" => "信件樣版",
	"model" => "master-data",
	"data_source" => "sys_mail_template",
	"item_control" => "ADD,FIX,DEL",
	"item_fields" => $mailtemplate_metas
);


//////////////////////////////////////////////////////////////////
//                            管理帳號
//////////////////////////////////////////////////////////////////


$admin_metas["account"] = array(
	"name" => "帳號",
	"variable" => "account",
	"type" => "text",
	"properties" => array("max" => 100),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);

$admin_metas["password"] = array(
	"name" => "密碼",
	"variable" => "password",
	"type" => "text",
	"properties" => array(),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);

$admin_metas["title"] = array(
	"name" => "聯絡人",
	"variable" => "title",
	"type" => "text",
	"properties" => array(),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);

$admin_metas["domain"] = array(
	"name" => "專屬網域",
	"variable" => "domain",
	"type" => "text",
	"properties" => array("max" => 255),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);

$admin_metas["google_id"] = array(
	"name" => "Google ID",
	"variable" => "google_id",
	"type" => "text",
	"properties" => array("max" => 100),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$admin_metas["facebook_id"] = array(
	"name" => "Facebook ID",
	"variable" => "facebook_id",
	"type" => "text",
	"properties" => array("max" => 100),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$configs["master_admin"] = array(
	"title" => "管理帳號",
	"model" => "master-data",
	"data_source" => "sys_admin",
	"item_control" => "ADD,FIX,DEL",
	"item_fields" => $admin_metas
);






//////////////////////////////////////////////////////////////////
//                            會員權限
//////////////////////////////////////////////////////////////////



$authority_metas["base_information"] = array(
	"name" => "基本設定",
	"variable" => "base_information",
	"type" => "grouplabel",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$authority_metas["title"] = array(
	"name" => "權限名稱",
	"variable" => "title",
	"type" => "text",
	"properties" => array("max" => 30),
	"xss" => true,
	"tip" => "",
	"default" => "",
	"list" => "Y"
);

/************************************************
 *                    項目設定
 ************************************************/

$authority_metas["authority_information"] = array(
	"name" => "權限設定",
	"variable" => "item_information",
	"type" => "grouplabel",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);

$authority_metas["authority_setting"] = array(
	"name" => "權限設定",
	"variable" => "authority_setting",
	"type" => "authority",
	"properties" => array(),
	"xss" => false,
	"tip" => "",
	"default" => "",
	"list" => "N"
);



$configs["master_authority"] = array(
	"title" => "權限管理",
	"model" => "master-data",
	"data_source" => "sys_authority",
	"item_control" => "ADD,FIX,DEL",
	"item_fields" => $authority_metas
);




?>