$(function() {
	var tsc = function() {
		var self = this;
		//self.backLayer = null;
		//图片数组
		self.imgData = new Array({
			name: "tiger",
			path: "img/tiger.jpg"
		}, {
			name: "stick",
			path: "img/stick.jpg"
		}, {
			name: "chicken",
			path: "img/chicken.jpg"
		});
		//判断器
		self.checkList = [
			[0, -1, 1],
			[1, 0, -1],
			[-1, 1, 0]
		];
		self.loss = 0;
		self.win = 0;
		self.draw = 0;

		self.showList = new Array();
		this.init = function() {
			LGlobal.setDebug(true);
			self.backLayer = new LSprite();
			addChild(self.backLayer);
			self.loaderLayer = new LoadingSample3();
			self.backLayer.addChild(self.loaderLayer);
			LLoadManage.load(self.imgData,
				function(progress) {
					self.loaderLayer.setProgress(progress);
				},
				function(result) {
					self.imgList = result;
					self.backLayer.removeChild(self.loaderLayer);
					self.loaderLayer = null;
					self.gameInit();
				});
		};

		this.gameInit = function() {
			for (var i = 0; i < self.imgData.length; i++)
				self.showList.push(new LBitmapData(self.imgList[self.imgData[i].name]));
			self.backLayer.graphics.drawRect(10, "#00800", [0, 0, LGlobal.width, LGlobal.height], true, "#ccc");
			//初始化标题
			var field = new LTextField();
			field.text = "老虎棒子鸡";
			field.size = 25;
			//field.width = 200;
			field.x = (LGlobal.width - field.width) / 2;
			field.y = 30;
			field.weight = "bolder";
			self.backLayer.addChild(field);
			//舞台区
			//玩家
			self.userimg = new LBitmap(self.showList[0]);
			self.userimg.x = 400 - self.userimg.width - 50;
			self.userimg.y = 130;
			self.backLayer.addChild(self.userimg);
			var username = new LTextField();
			username.text = "玩家";
			username.weight = "bolder";
			username.x = self.userimg.x + (self.userimg.width - username.getWidth()) / 2;
			username.y = 95;
			self.backLayer.addChild(username);
			//电脑
			self.enemyimg = new LBitmap(self.showList[1]);
			self.enemyimg.x = 450;
			self.enemyimg.y = 130;
			self.backLayer.addChild(self.enemyimg);
			var enemyname = new LTextField();
			enemyname.text = "电脑";
			enemyname.weight = "bolder";
			enemyname.x = self.enemyimg.x + (self.enemyimg.width - enemyname.getWidth()) / 2;
			enemyname.y = 95;
			self.backLayer.addChild(enemyname);
			//初始化结果层
			self.initResultLayer();
			//初始化操作层
			self.initClickLayer();
		};

		this.initResultLayer = function() {
			self.resultLayer = new LSprite();
			self.resultLayer.graphics.drawRect(4, "#ff8800", [0, 0, 150, 150], true, "#fff");
			self.resultLayer.x = 10;
			self.resultLayer.y = 100;
			self.backLayer.addChild(self.resultLayer);

			self.txt_count = new LTextField();
			self.txt_count.text = "猜拳次数:0";
			self.txt_count.x = 10;
			self.txt_count.y = 20;
			self.resultLayer.addChild(self.txt_count);

			self.txt_win = new LTextField();
			self.txt_win.text = "胜利次数:0";
			self.txt_win.x = 10;
			self.txt_win.y = 40;
			self.resultLayer.addChild(self.txt_win);

			self.txt_loss = new LTextField();
			self.txt_loss.text = "失败次数:0";
			self.txt_loss.x = 10;
			self.txt_loss.y = 60;
			self.resultLayer.addChild(self.txt_loss);

			self.txt_draw = new LTextField();
			self.txt_draw.text = "平局次数:0";
			self.txt_draw.x = 10;
			self.txt_draw.y = 80;
			self.resultLayer.addChild(self.txt_draw);
		};

		this.initClickLayer = function() {
			self.clickLayer = new LSprite();
			self.clickLayer.graphics.drawRect(4, "#ff8800", [0, 0, 300, 110], true, "#fff");
			self.clickLayer.x = 250;
			self.clickLayer.y = 275;
			self.backLayer.addChild(self.clickLayer);

			var msgtxt = new LTextField();
			msgtxt.text = "请选择";
			msgtxt.x = 10;
			msgtxt.y = 10;
			self.clickLayer.addChild(msgtxt);

			var btn_tiger = self.makeButton("tiger");
			btn_tiger.x = 30;
			btn_tiger.y = 35;
			self.clickLayer.addChild(btn_tiger);
			btn_tiger.addEventListener(LMouseEvent.MOUSE_UP, self.onClick);

			var btn_stick = self.makeButton("stick");
			btn_stick.x = 115;
			btn_stick.y = 35;
			self.clickLayer.addChild(btn_stick);
			btn_stick.addEventListener(LMouseEvent.MOUSE_UP, self.onClick);

			var btn_chicken = self.makeButton("chicken");
			btn_chicken.x = 200;
			btn_chicken.y = 35;
			self.clickLayer.addChild(btn_chicken);
			btn_chicken.addEventListener(LMouseEvent.MOUSE_UP, self.onClick);

		};

		this.makeButton = function(val) {
			var btnUp = new LBitmap(new LBitmapData(self.imgList[val]));
			btnUp.scaleX = 0.5;
			btnUp.scaleY = 0.5;
			var btnOver = new LBitmap(new LBitmapData(self.imgList[val]));
			btnOver.scaleX = 0.5;
			btnOver.scaleY = 0.5;
			btnOver.x = 2;
			btnOver.y = 2;
			var btn = new LButton(btnUp, btnOver);
			btn.name = val;
			return btn;
		};

		this.onClick = function(event, display) {
			var selfval, enemyval;
			if (display.name == "chicken") {
				selfval = 0;
			} else if (display.name == "stick") {
				selfval = 1;
			} else if (display.name == "tiger") {
				selfval = 2;
			}
			enemyval = Math.floor(Math.random() * 3);
			self.userimg.bitmapData = self.showList[selfval];
			self.enemyimg.bitmapData = self.showList[enemyval];
			var result = self.checkList[selfval][enemyval];
			if (result == -1)
				self.loss++;
			else if (result == 1)
				self.win++;
			else
				self.draw++;
			self.txt_count.text = "猜拳次数:" + (self.loss + self.win + self.draw);
			self.txt_loss.text = "失败次数:" + self.loss;
			self.txt_win.text = "胜利次数:" + self.win;
			self.txt_draw.text = "平局次数:" + self.draw;

		};
	};

	function main() {
		new tsc().init();
	};

	init(50, "game_tsc", 800, 400, main);
});