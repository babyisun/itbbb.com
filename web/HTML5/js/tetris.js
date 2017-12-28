$(function() {
	var tsc = function() {
		var self = this;
		//图片数组
		self.imgData = [];
		self.showList = new Array();
		self.map = [];
		self.speed = 450;
		self.del = 0;
		self.point = 0;

		this.init = function() {
			self.backLayer = new LSprite();
			self.backLayer.graphics.drawRect(1, "#000", [0, 0, 320, 480], 1, "#ccc");
			addChild(self.backLayer);
			var title = new LTextField();
			title.size = 30;
			title.color = "#fff";
			title.text = "俄罗斯方块";
			title.x = (LGlobal.width - title.width) / 2;
			title.y = 100;
			self.backLayer.addChild(title);

			var label_start = new LTextField();
			label_start.size = 18;
			label_start.color = "#fff";
			label_start.text = "点击屏幕开始游戏";
			label_start.x = (LGlobal.width - label_start.width) / 2;
			label_start.y = 250;
			self.backLayer.addChild(label_start);
			self.backLayer.graphics.drawRect(1, "#fff", [50, 245, 220, 40]);
			self.backLayer.addEventListener(LMouseEvent.MOUSE_UP, self.gameStart);
			//地图
			//self.mapInit();


			//装载图片
			for (var i = 1; i < 6; i++) {
				self.imgData.push({
					name: "box" + i,
					path: "img/tetris/s" + i + ".png"
				});
			}


			LGlobal.setDebug(true);
			self.backLayer = new LSprite();
			addChild(self.backLayer);
			self.loaderLayer = new LoadingSample1();
			self.backLayer.addChild(self.loaderLayer);
			LLoadManage.load(self.imgData,
				function(progress) {
					self.loaderLayer.setProgress(progress);
				},
				function(result) {
					self.imgList = result;
					self.backLayer.removeChild(self.loaderLayer);
					self.loaderLayer = null;
					//图片装入缓存
					for (var i = 0; i < self.imgData.length; i++) {
						var _name = self.imgData[i].name;
						self.showList.push(new LBitmapData(self.imgList[_name]));
					}
					//self.gameInit();
				});
		};

		this.drawMap = function() {
			//初始化地图10*20

			for (var m = 0; m < 20; m++) {
				var arr = new Array();
				for (var n = 0; n < 10; n++) {
					arr.push(0);
				}
				self.map.push(arr);
			}
			self.nodeList = [];
			self.BOX_SIDE = 20;
			var nArr,
				bitmap,
				START_X = 15,
				START_Y = 20;
			for (var i = 0; i < self.map.length; i++) {
				nArr = [];
				for (var j = 0; j < self.map[i].length; j++) {
					var _x = j * self.BOX_SIDE + START_X,
						_y = i * self.BOX_SIDE + START_Y;
					self.backLayer.graphics.drawRect(1, "#ddd", [_x, _y, self.BOX_SIDE, self.BOX_SIDE], 1, "#e5eff2");
					bitmap = new LBitmap();
					bitmap.x = _x;
					bitmap.y = _y;
					self.backLayer.addChild(bitmap);
					nArr[j] = {
						index: -1,
						value: 0,
						bitmap: bitmap
					};

				}
				self.nodeList[i] = nArr;
			}
		};


		this.mapInit = function() {
			self.drawMap();

			self.controlLayer = new LSprite();
			self.controlLayer.x = 220;
			self.controlLayer.y = 20;
			self.controlLayer.graphics.drawRect(1, "#fff", [0, 0, 90, 400], true, "#ff8800");
			self.backLayer.addChild(self.controlLayer);

			var label_next = new LTextField();
			label_next.text = "NEXT";
			label_next.size = 30;
			label_next.x = 5;
			label_next.y = 5;
			self.controlLayer.addChild(label_next);

			self.nextLayer = new LSprite();
			self.nextLayer.x = 5;
			self.nextLayer.y = 40;
			var START_CX = 0,
				START_CY = 0;
			for (var i = 0; i < 4; i++) {
				for (var j = 0; j < 4; j++) {
					self.nextLayer.graphics.drawRect(1, "#ddd", [j * self.BOX_SIDE + START_CX, i * self.BOX_SIDE + START_CY, self.BOX_SIDE, self.BOX_SIDE], 1, "#e5eff2");
				}
			}
			self.controlLayer.addChild(self.nextLayer);


			self.controlLayer.graphics.drawRect(1, "#fff", [5, 140, 80, 50], true, "#ccc");
			self.label_score = new LTextField();
			self.label_score.text = "得分：0";
			self.label_score.x = 5;
			self.label_score.y = 150;
			self.controlLayer.addChild(self.label_score);

			self.controlLayer.graphics.drawRect(1, "#fff", [5, 200, 80, 50], true, "#ccc");
			self.label_level = new LTextField();
			self.label_level.text = "难度：1";
			self.label_level.x = 5;
			self.label_level.y = 210;
			self.controlLayer.addChild(self.label_level);

			self.controlLayer.graphics.drawRect(1, "#fff", [5, 260, 80, 50], true, "#ccc");
			self.label_floor = new LTextField();
			self.label_floor.text = "消除：0";
			self.label_floor.x = 5;
			self.label_floor.y = 270;
			self.controlLayer.addChild(self.label_floor);

		};
		//游戏事件
		this.Event = {
			touch: {},
			onkeydown: function(event) {
				if (self.key != null) return;
				switch (event.keyCode) {
					case 32:
						self.key = "space";
						break;
					case 37:
						self.key = "left";
						break;
					case 38:
						self.key = "up";
						break;
					case 39:
						self.key = "right";
						break;
					case 40:
						self.key = "down";
						break;
				}
			},
			onkeyup: function(event) {
				self.key = null;
				self.stepindex = 0;
			},
			touchDown: function(event) {
				self.Event.touch.isTouchDown = true;
				self.Event.touch._x = Math.floor(event.selfX / 20);
				self.Event.touch._y = Math.floor(event.selfY / 20);
				self.Event.touch.touchMove = false;
				self.key = null;
			},
			touchUp: function(event) {
				self.Event.touch.isTouchDown = false;
				if (!self.Event.touch.touchMove)
					self.key = "up";
			},
			touchMove: function(event) {
				if (!self.Event.isTouchDown) return;
				var mx = Math.floor(event.selfX / 20);
				if (self.Event.touch._x == 0) {
					self.Event.touch._x = mx;
					self.Event.touch._y = Math.floor(event.selfY);
				}
				if (mx > self.Event.touch._x) {
					self.key = "right";
				} else if (mx < self.Event.touch._x) {
					self.key = "left";
				}
				if (Math.floor(event.selfx / 20) > self.Event.touch._y) {
					self.key = "down";
				}
			},
			changeBox: function() {
				var saveBox = self.nowBox;
				self.nowBox = [
					[0, 0, 0, 0],
					[0, 0, 0, 0],
					[0, 0, 0, 0],
					[0, 0, 0, 0]
				];
				for (var i = 0; i < self.nowBox.length; i++) {
					for (var j = 0; j < self.nowBox[i].length; j++) {
						self.nowBox[i][j] = saveBox[3 - j][i];
					}
				}
				if (!self.checkPlus(0, 0)) {
					self.nowBox = saveBox;
				}
			},
			init: function() {
				self.backLayer.addEventListener(LEvent.ENTER_FRAME, self.onFrame);
				self.backLayer.addEventListener(LMouseEvent.MOUSE_DOWN, self.Event.touchDown);
				self.backLayer.addEventListener(LMouseEvent.MOUSE_UP, self.Event.touchDown);
				self.backLayer.addEventListener(LMouseEvent.MOUSE_MOVE, self.Event.touchMove);
				if (!LGlobal.canTouch) {
					LEvent.addEventListener(LGlobal.window, LKeyboardEvent.KEY_DOWN, self.Event.onkeydown);
					LEvent.addEventListener(LGlobal.window, LKeyboardEvent.KEY_UP, self.Event.onkeyup);
				}
			}
		};
		//游戏开始
		this.gameStart = function() {
			self.backLayer.die();
			self.backLayer.removeAllChild();
			self.backLayer.graphics.drawRect(0, "#000", [0, 0, 320, 480], true, "#000");
			self.mapInit();
			self.gameInit();
			// var a=new Box();
			// var b=a.box1;
		};
		//游戏结束
		this.gameOver = function() {
			//self.backLayer.die();
			var txt = new LTextField();
			txt.color = "#ff0000";
			txt.size = 40;
			txt.text = "游戏结束";
			txt.x = (LGlobal.width - txt.width) / 2;
			txt.y = 200;
			self.backLayer.addChild(txt);
			self.backLayer.removeEventListener(LEvent.ENTER_FRAME, self.onFrame);
		};
		//游戏初始化
		this.gameInit = function() {
			self.getNewBox();
			self.Event.init();
		};

		this.onFrame = function() {
			self.minusBox();
			//事件
			if (self.key != null) {
				switch (self.key) {
					case "left":
						if (self.checkPlus(-1, 0))
							self.pointBox.x--;
						break;

					case "right":
						if (self.checkPlus(1, 0))
							self.pointBox.x++;
						break;
					case "down":
						if (self.checkPlus(0, 1))
							self.pointBox.y++;
						break;
					case "up":
						self.Event.changeBox();
						break;
					case "space":
						self.Event.changeBox();
						break;
				}

			}

			//if (speedIndex++ > speed)
			//	speedIndex = 0;
			if (self.checkPlus(0, 1)) {
				self.pointBox.y++;
			} else {
				//无法下移动
				self.plusBox(); //真plus
				self.removeBox();//判断是否有消除层
				if (self.pointBox.y < 0) {
					self.gameOver();
					return;
				}
				//self.minusBox();
				self.getNewBox();
			}
			self.plusBox();
			self.drawPointBox();
		};

		this.getNewBox = function() {
			self.BOX = new Box();
			//当前box
			if (!self.nextBox) {
				self.nextBox = self.BOX.getBox();
			}
			self.nowBox = self.nextBox; //当前块
			self.pointBox = {}; //self.nowBox;
			self.pointBox.x = 3;
			self.pointBox.y = -4;
			//渲染下一个
			self.nextBox = self.BOX.getBox();
			self.nextLayer.removeAllChild();
			var bitmap;
			for (var i = 0; i < self.nextBox.length; i++) {
				for (var j = 0; j < self.nextBox[i].length; j++) {
					if (self.nextBox[i][j] == 0)
						continue;
					bitmap = new LBitmap(self.showList[self.nextBox[i][j] - 1]);
					bitmap.x = bitmap.width * j;
					bitmap.y = bitmap.height * i;
					self.nextLayer.addChild(bitmap);
				}
			}

		};

		this.plusBox = function() {
			for (var i = 0; i < self.nowBox.length; i++) {
				for (var j = 0; j < self.nowBox[i].length; j++) {
					if (i + self.pointBox.y < 0 || i + self.pointBox.y >= self.map.length || j + self.pointBox.x < 0 || j + self.pointBox.x >= self.map[0].length) {
						continue;
					}

					self.map[i + self.pointBox.y][j + self.pointBox.x] = self.nowBox[i][j] + self.map[i + self.pointBox.y][j + self.pointBox.x];
					self.nodeList[i + self.pointBox.y][j + self.pointBox.x].index = self.map[i + self.pointBox.y][j + self.pointBox.x] - 1;
					//console.log((i + self.pointBox.y) + "," + (j + self.pointBox.x) + ":" + self.map[i + self.pointBox.y][j + self.pointBox.x]);
				}
			}
		};

		this.minusBox = function() {
			for (var i = 0; i < self.nowBox.length; i++) {
				for (var j = 0; j < self.nowBox[i].length; j++) {
					if (i + self.pointBox.y < 0 || i + self.pointBox.y >= self.map.length || j + self.pointBox.x < 0 || j + self.pointBox.x >= self.map[0].length) {
						continue;
					}
					self.map[i + self.pointBox.y][j + self.pointBox.x] = self.map[i + self.pointBox.y][j + self.pointBox.x] - self.nowBox[i][j];
					self.nodeList[i + self.pointBox.y][j + self.pointBox.x].index = self.map[i + self.pointBox.y][j + self.pointBox.x] - 1;
				}
			}
		};

		this.checkPlus = function(nx, ny) {
			for (var i = 0; i < self.nowBox.length; i++) {
				for (var j = 0; j < self.nowBox[i].length; j++) {
					//超出xy轴
					if (i + self.pointBox.y + ny < 0) {
						continue;
					} else if (i + self.pointBox.y + ny >= self.map.length || j + self.pointBox.x + nx < 0 || j + self.pointBox.x + nx >= self.map[0].length) {
						if (self.nowBox[i][j] == 0) //不占位的坐标
							continue;
						else
							return false;
					}
					//地图上是否已经存在方块
					if (self.nowBox[i][j] > 0 && self.map[i + self.pointBox.y + ny][j + self.pointBox.x + nx] > 0) {
						//console.log("[" + (i + self.pointBox.y + ny) + "," + (j + self.pointBox.x + nx) + "]当前nowbox：" + self.nowBox[i][j] + ",mapbox：" + self.map[i + self.pointBox.y + ny][j + self.pointBox.x + nx]);
						return false;

					}
				}
			}
			return true;
		};

		this.drawPointBox = function() {
			//var log = "";
			for (var i = 0; i < self.map.length; i++) {
				for (var j = 0; j < self.map[i].length; j++) {
					var _index = self.nodeList[i][j].index;
					if (_index >= 0) {
						self.nodeList[i][j].bitmap.bitmapData = self.showList[_index];
					} else {
						self.nodeList[i][j].bitmap.bitmapData = null;
					}
					//log += self.map[i][j] + ",";
				}
				//log += "\r\n";
			}
			//console.log(log);
		};

		this.moveLine = function(line) {
			for (var i = line; i > 1; i--) {
				for (var j = 0; j < self.map[0].length; j++) {
					self.map[i][j] = self.map[i - 1][j];
					self.nodeList[i][j].index = self.nodeList[i - 1][j].index;
				}
			}
			for (var m = 0; m < self.map[0].length; m++) {
				self.map[0][m] = 0;
				self.nodeList[0][m].index = -1;
			}
		};

		this.removeBox = function() {
			var count = 0;
			for (var i = self.pointBox.y; i < (self.pointBox.y + 4); i++) {
				if (i < 0 || i >= self.map.length)
					continue;
				for (var j = 0; j < self.map[0].length; j++) {
					if (self.map[i][j] == 0)//第j块=0则有空当
						break;
					//所有j循环到最后一块而没有break，则清除
					if (j == self.map[0].length - 1) {
						self.moveLine(i);
						count++;
					}
				}
			}
			if (count == 0)
				return;
			self.del += count;
			if (count == 1) self.point++;
			else if (count == 2) self.point += 3;
			else if (count == 3) self.point += 6;
			else if (count == 4) self.point += 10;

			if (self.speed > 50)
				self.speed = (9 - self.del / 10) * 50;

			self.showText();
		};

		this.showText = function() {
			self.label_score.text = "得分：" + self.point;
			self.label_level.text = "难度：" + (parseInt(self.point / 100) + 1);
			self.label_floor.text = "消除：" + self.del;
		};


		//砖块类
		var Box = function() {
			this.box1 = [
				[0, 0, 0, 0],
				[0, 0, 0, 0],
				[1, 1, 1, 1],
				[0, 0, 0, 0]
			];
			this.box2 = [
				[0, 0, 0, 0],
				[0, 1, 1, 0],
				[0, 1, 1, 0],
				[0, 0, 0, 0]
			];
			this.box3 = [
				[0, 0, 0, 0],
				[0, 1, 0, 0],
				[1, 1, 1, 0],
				[0, 0, 0, 0]
			];
			this.box4 = [
				[0, 1, 1, 0],
				[0, 1, 0, 0],
				[0, 1, 0, 0],
				[0, 0, 0, 0]
			];
			this.box5 = [
				[0, 1, 1, 0],
				[0, 0, 1, 0],
				[0, 0, 1, 0],
				[0, 0, 0, 0]
			];
			this.box6 = [
				[0, 0, 0, 0],
				[0, 1, 0, 0],
				[0, 1, 1, 0],
				[0, 0, 1, 0]
			];
			this.box7 = [
				[0, 0, 0, 0],
				[0, 0, 1, 0],
				[0, 1, 1, 0],
				[0, 1, 0, 0]
			];
			this.box = [this.box1, this.box2, this.box3, this.box4, this.box5, this.box6, this.box7];
		};
		Box.prototype = {
			getBox: function() {
				var _this = this,
					result = [],
					boxIndex = parseInt(7 * Math.random()), //随机形状块
					colorIndex = 1 + Math.floor(Math.random() * 5); //随机颜色块
				for (var i = 0; i < 4; i++) {
					var child = [];
					for (var j = 0; j < 4; j++) {
						child[j] = _this.box[boxIndex][i][j] * colorIndex;
					}
					result[i] = child;
				}
				return result;
			}
		};



	};

	function main() {
		new tsc().init();
	};

	init(450, "game_tetris", 320, 480, main);
});