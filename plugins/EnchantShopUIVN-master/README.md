Download: <a href="https://github.com/GamerSoiCon/EnchantShopUIVN/releases">EnchantShopUI Việt Hóa</a>

#### Lệnh
+ /enchantui: để mở shop.

#### Permission
+ eshop.command: Cho phép sử dụng EnchantUI.

#### Config:
+ Bạn có thể sử dụng enchant từ PiggyCustomEnchants.
```
---
#Đừng thay đổi cái này
version: "0.6"

#Hiện form EnchantUI nếu người chơi chạm vào cái bàn phù phép.
enchanting-table: true

#EnchantUI phiên bản 0.5
Title: "§cCửa hàng phù phép"
Button: "§3{NAME} §a{PRICE}$"
slider-title: "§5Cấp độ"
messages:
  thanks: "§aCảm ơn bạn đã sử dụng cửa hàng phù phép"
  label: "§eBạn sẽ phải trả {PRICE}$ cho mỗi cấp độ enchant"
  not-enough-money: "§cBạn không có đủ tiền!"
  paid-success: "§aBạn đã mua {NAME} cấp độ {LEVEL} với {PRICE}$"
  hold-item: "§cVui lòng cầm một vật phẩm có thể enchant"
  no-perm: "§cBạn không có quyền để làm điều này!"
  incompatible-enchantment:
  
#CẢNH BÁO: Chỉ sử dụng ID enchant cho phần incompatible-enchantments, ví dụ: [1,10,13]
shop:	
- name: "Phòng cháy chữa cháy bà con êy"
  enchantment: fire_protection
  price: 100
  max-level: 2
  incompatible-enchantments: []
- name: "Điện máy xanh u qua u qua u qua"
  enchantment: 1
  price: 100
  max-level: 3
  incompatible-enchantments: []
...
```

#### Youtube
[![Watch the video](https://img.youtube.com/vi/fn9iZL0q5yk/maxresdefault.jpg)](https://youtu.be/fn9iZL0q5yk)
