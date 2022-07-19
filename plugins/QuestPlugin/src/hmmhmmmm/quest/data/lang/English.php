<?php

namespace hmmhmmmm\quest\data\lang;

class English{

   public static function init(): array{
      return [
         "reset" => false,
         "version" => 1,
         "notfound.plugin" => "§cThis plugin will not work, Please install the plugin %1",
         "notfound.libraries" => "§cLibraries %1 not found, Please download this plugin to as .phar",
         "plugininfo.name" => "§a§l%1",
         "plugininfo.version" => "§fVersion %1",
         "plugininfo.author" => "§fList of creators %1",
         "plugininfo.description" => "§fDescription of the plugin. §eis a plugin free. If you redistribute it, please credit the creator. :)",
         "plugininfo.facebook" => "§fFacebook %1",
         "plugininfo.youtube" => "§fYoutube %1",
         "plugininfo.website" => "§fWebsite %1",
         "plugininfo.discord" => "§fDiscord %1",
         "playerquest.info.error1" => "§cLỗi: %1",
         "playerquest.info.text1" => "§e§lNhiệm vụ §b%1",
         "playerquest.info.text2" => "§c§lTiến độ : §a%1/%2",
         "playerquest.info.text3" => "§b§l Mô tả : %1",
         "playerquest.info.text4" => "§f§lPhần thưởng: §b%1%2",
         "questdata.info.text1" => "§b§l%1",
         "questdata.info.text2" => "§e§lMô tả : %1",
         "questdata.info.text3" => "§a§lSố lượng cần làm : [ §a%1 ]",
         "questdata.info.text4" => "§d§lPhần Thưởng : §b%1",
         "questmanager.eventlist.breakblock" => "§a[§e• §cPHÁ VỠ KHỐI§e •§a]",
         "questmanager.eventlist.placeblock" => "§a[§e• §cĐẶT KHỐI§e •§a]",
         "questmanager.eventlist.kill_entity" => "§a[§e• §cGIẾT SINH VẬT§e •§a]",
         "questmanager.eventlist.kill_player" => "§a[§e• §cGIẾT NGƯờI CHƠI§e •§a]",
         "questmanager.eventlist.trading.text1" => "Thương mại",
         "questmanager.eventlist.trading.text2" => "Vui lòng giữ vật phẩm.",
         "questmanager.eventlist.online" => "§a[§e• §cONLINE§e •§a]",
         "questmanager.start.complete" => "§d%1 §fđã hoàn thành nhiệm vụ §e%2 §fvà nhận được phần thưởng %3",
         "command.consoleError" => "§cSorry: commands can be typed only in the game.",
         "command.permissionError" => "§cSorry: You cannot type this command.",
         "command.sendHelp.empty" => "§eBạn có thể dùng §c/quest help §eđể xem toàn bộ lệnh",
         "command.info.usage" => "/quest info",
         "command.info.description" => "§fThông tin người làm plugin",
         "command.list.usage" => "/quest list",
         "command.list.description" => "§f§fXem tất cả nhiệm vụ đang có.",
         "command.list.error1" => "§cBạn hiện tại không có nhiệm vụ",
         "command.remove.usage" => "/quest remove <QuestNname>",
         "command.remove.description" => "§f§fXoá nhiệm vụ",
         "command.remove.error1" => "§cLỗi: %1",
         "command.remove.error2" => "§cBạn hiện tại không có nhiệm vụ",
         "command.remove.error3" => "§cTên nhiệm vụ §e%1 §ckhông tồn tại",
         "command.remove.complete" => "Bạn đã xoá thành công nhiệm vụ §c%1.!",
         "command.add.usage" => "/quest add <PlayeName> <QuestName>",
         "command.add.description" => "§fThêm nhiệm vụ cho người chơi khác",
         "command.add.error1" => "§cLỗi: %1",
         "command.add.error2" => "§cNgười chơi không tồn tại hoặc không online",
         "command.add.error3" => "§cTên nhiệm vụ §e%1§c không tồn tại!",
         "command.add.error4" => "§cBạn đã hoàn thành nhiệm vụ..",
         "command.add.error5" => "§cCần dùng vật phẩm §e%1 §cđể trao đổi",
         "command.add.complete1" => "Người chơi §b%1 §fđã nhận nhiệm vụ §a%2",
         "command.add.complete2" => "Bạn đã nhận nhiệm vụ §a%1",
         "command.data.usage" => "/quest data create|remove|list|setlimit|addlimit",
         "command.data.error1" => "§cLỗi: %1",
         "command.data.event.usage" => "/quest data event",
         "command.data.event.description" => "§fXem các sự kiện khác nhau",
         "command.data.event.complete" => "§fSự kiện §b%1 §f%2",
         "command.data.create.usage" => "/quest data create <NameTheQuest> <Max> <Event> <DescriptiveText> <AwardMessage> <CommandAward>",
         "command.data.create.description" => "§fTạo nhiệm vụ",
         "command.data.create.error1" => "§cLỗi: %1",
         "command.data.create.error2" => "§cTên nhiệm vụ đã tồn tại.",
         "command.data.create.error3" => "§c<Max> Vui lòng ghi số!.",
         "command.data.create.error4" => "§cSự kiện không tồn tại %1",
         "command.data.create.error5" => "§cVui lòng cầm Item.",
         "command.data.create.complete1" => "Nhiệm vụ §a%1 §fsự kiện §b%2 (%3) §fđã được tạo!",
         "command.data.create.complete2" => "Nhiệm vụ §a%1 §fsự kiện §b%2 (%3) §fđã được tạo bởi dùng item §d%4",
         "command.data.remove.usage" => "/quest data remove <Quest name>",
         "command.data.remove.description" => "§fXoá nhiệm vụ",
         "command.data.remove.error1" => "§cLỗi: %1",
         "command.data.remove.error2" => "§cTên nhiệm vụ §e%1 §ckhông tồn tại",
         "command.data.remove.complete" => "Đã xoá thành công nhiệm vụ %1!",
         "command.data.list.usage" => "/quest data list",
         "command.data.list.description" => "§fXem toàn bộ danh sách nhiệm vụ.",
         "command.data.list.error1" => "§cKhông có nhiệm vụ",
         "command.data.list.complete" => "Nhiệm vụ §b%1 §fđã hoàn thành",
         "command.data.setlimit.usage" => "/quest data setlimit <QuestName>",
         "command.data.setlimit.description" => "§fGiới hạn cho người chơi chỉ hoàn thành nhiệm vụ này một lần",
         "command.data.setlimit.error1" => "§cLỗi: %1",
         "command.data.setlimit.error2" => "§cTên nhiệm vụ §e%1§c không tồn tại",
         "command.data.setlimit.complete" => "Nhiệm vụ §a%1 §fđã được tạo giới hạn thành công!",
         "command.data.addlimit.usage" => "/quest data addlimit <QuestName> <PlayerName>",
         "command.data.addlimit.description" => "§fThêm giới hạn nhiệm vụ cho người chơi khác",
         "command.data.addlimit.error1" => "§cLỗi: %1",
         "command.data.addlimit.error2" => "§cTên nhiệm vụ §e%1 §ckhông tồn tại",
         "command.data.addlimit.error3" => "§cĐây là nhiệm vụ không giới hạn",
         "command.data.addlimit.error4" => "§cTên người chơi này đã tồn tại..",
         "command.data.addlimit.complete" => "Nhiệm vụ §a%1 §fđã được thêm cho người chơi §e%2 §fđến giới hạn",
         "command.data.slapper_get.usage" => "/quest data slapper_get <QuestName>",
         "command.data.slapper_get.description" => "§fTạo NPC đưa nhiệm vụ cho người chơi",
         "command.data.slapper_get.error1" => "§cLỗi: %1",
         "command.data.slapper_get.error2" => "§cTên nhiệm vụ §e%1 §ckhông tồn tại",
         "form.menu.button1" => "§eNhiệm Vụ",
         "form.menu.button2" => "§fBạn đang có [ §a%1§f ] nhiệm vụ",
         "form.menu.error1" => "§cLỖI\n§eBạn không được phép sử dụng",
         "form.menu.error2" => "§cLỖI\n§eBạn đang không có nhiệm vụ",
         "form.edit.button1" => "§e[§cXoá Nhiệm Vụ§e]",
         "form.remove.content" => "§fBạn có chắc muốn xoá nhiệm vụ §a%1 §fkhông?",
         "form.remove.button1" => "§aCó",
         "form.remove.button2" => "§cKhông",
         "form.data.menu.button1" => "§aTạo Nhiệm Vụ",
         "form.data.event.content" => "§eVui lòng chọn sự kiện.",
         "form.data.create.input1" => "§eTên Nhiệm Vụ",
         "form.data.create.input2" => "§eSố Lượng",
         "form.data.create.input3" => "§eMô Tả",
         "form.data.create.input4" => "§ePhần thưởng",
         "form.data.create.input5" => "§eLệnh phần thưởng",
         "form.data.create.error1" => "§cLỖI\n§e<Nhiệm Vụ> Vui lòng nhập tên nhiệm vụ",
         "form.data.create.error2" => "§cLỖI\n§e<Tên Nhiệm Vụ> §cTên nhiệm vụ §e%1 §cđã tồn tại",
         "form.data.create.error3" => "§cLỖI\n§e<Số Lượng> Vui lòng viết bằng số!",
         "form.data.create.error4" => "§cLỖI\n§e<Mô Tả> Cần Phải Nhập",
         "form.data.create.error5" => "§cLỖI\n§e§e<Phần Thưởng> Cần Phải Nhập",
         "form.data.create.error6" => "§cLỖI\n§e<Lệnh Phần Thưởng> Cần Phải Nhập",
         "form.data.create.error7" => "§cLỖI\n§eVUI LÒNG CẦM TRÊN TAY",
         "form.data.edit.button1" => "§fChỉnh Sửa Nhiệm Vụ",
         "form.data.edit.button2" => "§fĐặt Giới Hạn Người Chơi Hoàn Thành Nhiệm Vụ.",
         "form.data.edit.button3" => "§fReset Giới Hạn",
         "form.data.edit.button4" => "§fXoá Giới Hạn",
         "form.data.edit.button5" => "§fTạo NPC Đưa Nhiệm Vụ",
         "form.data.edit.button6" => "§e[ §cXOÁ NHIỆM VỤ§e ]",
         "form.data.edit.resetlimit.complete" => "Nhiệm vụ §a%1 §fđã được reset giới hạn thành công",
         "form.data.edit.unlimit.complete" => "Nhiệm vụ §a%1 §fđã được xoá giới hạn thành công",
         "questutils.makeslapper.complete" => "§aĐã tạo NPC đưa nhiệm vụ thành công!"
      ];
   }
   
}