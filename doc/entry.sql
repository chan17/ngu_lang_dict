/*
Navicat SQLite Data Transfer

Source Server         : AndrewNg
Source Server Version : 31300
Source Host           : :0

Target Server Type    : SQLite
Target Server Version : 31300
File Encoding         : 65001

Date: 2017-04-22 00:09:01
*/

PRAGMA foreign_keys = OFF;

-- ----------------------------
-- Table structure for entry
-- ----------------------------
DROP TABLE IF EXISTS "main"."entry";
CREATE TABLE "entry" (
-- 主鍵
"id"  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
-- 字詞 來源，如標準無語言、吳語方言
"source"  CHAR(10) NOT NULL,
-- 繁體字
"zh_tw"  CHAR(8),
-- 簡體字
"zh_cn"  CHAR(8),
-- 音標，多個音標用 + 隔開，如：lik4(吳音)+ven3(漢音)
"phonetic"  CHAR(22) NOT NULL,
-- 字詞解釋 多個字詞解釋用 + 隔開
"explanation"  varchar
);

-- ----------------------------
-- Indexes structure for table entry
-- ----------------------------
CREATE UNIQUE INDEX "main"."id"
ON "entry" ("id" ASC);
PRAGMA foreign_keys = ON;
