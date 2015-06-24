/**
 * @author pooky
 * @description 组件包装
 */

;( function () {

	var components = {
		/**
		 * @author jason
		 * @des 选择城市组件
		 */
		//省市控件包装器
		CityWrapper:function(){
			
			//样式名称
			var CLASSES = {
				//选中状态
				active:'mr_on'
			}

			// 静态、私有、常量
			var CONSTANTS = {

				//热门城市列表
				hotCitys: '<li>北京</li><li>上海</li><li>广州</li><li>深圳</li><li>杭州</li><li>成都</li><li>西安</li><li>南京</li><li>厦门</li><li>武汉</li>'
				
			};		

			//构造器
			var CityWrapperConstructor = function ( opt ) {

				// 私有属性池
				var _pool = {  };

				// 私有方法 _get 和 _set【此两个方法不应该从外部调用】
				this._get = function ( key ) {
					return _pool[ key ];
				};
				this._set = function ( key, value ) {
					var me = this;
					_pool[ key ] = value;
					return me;
				};

				// 初始化
				_init.call( this, opt );
			}

			// 私有方法，初始化方法也是对外不暴露了
			var _init = function ( opt ) {

				var me = this;

				if ( !opt.container )
					throw 'Need options.container!';						

				var oProvinceCity = {};
				//初始化热门城市列表
				opt.container.find(".mr_province li:first ul").html(CONSTANTS.hotCitys);
				//获取所有包裹省市键值对的li
				var  oLis = opt.container.find(".mr_province li");
				//将所有省市键值对 缓存在js对象中
				oLis.each( function () {
					var target = $( this );

					oProvinceCity[target.find("span") ] = target.find("ul");

				} );

				me._set( 'container', opt.container )
					._set( 'onchange', opt.onchange )
					//城市展示
					._set( 'targetShown', opt.container.find( 'input[type="button"]' ) )
					//基本信息页面
					._set('basicCity' ,opt.container.find( 'input[name="workCity"]' ) )
					._set( 'beforeShown', opt.beforeShown )
					._set( 'afterHide', opt.afterHide )
					// cityContainer
					._set( 'targetPanel', opt.container.find( '.xl_list' ) )
					// input中的值
					._set( 'value', undefined)
					._set('targetProvinceWrap',opt.container.find( '.mr_province' ) )
					//存储所有省份对象
					._set('targetProvinces',opt.container.find( '.mr_province span' ) )
					//存储所有城市的包裹对象ul
					._set('targetCityWrap',opt.container.find( '.mr_province ul' ) )
					//存储所有城市的包裹对象
					._set('targetCityObjs',opt.container.find( '.mr_province ul li' ) )
					//热门城市对象集合
					._set('targetHotCitys',opt.container.find( '.mr_province li:first  ul li' ))
					//热门城市对象包裹
					._set('targetHotCityUl',opt.container.find( '.mr_province li:first  ul' ))
					//省份列表中的热门城市对象
					._set('targetHotCityLeft',opt.container.find( '.mr_province li:first  span' ))
					//将所有省市键值对集合缓存
					._set('oProvinceCity',oProvinceCity)
					//所有城市的直接集合ul
					._set('targetCityUl',opt.container.find( '.mr_province li ul' ))

				// 初始化动作
				_behaver.call( me );
			}	

			var _behaver = function () {

				var me = this;


				// 为现在的日期项添加对应的class
				//_patchTodayCls.call( me );

				// 事件绑定
				_bindEvents.call( me );

			};

			// 触发器
			var _triggers = {

				hide: function ( me ) {

					if ( !me._get( 'stateShown' ) ) 
						return;

					me._get( 'targetPanel' ).hide();
					// 隐藏后的回调
					var afterHide = me._get( 'afterHide' );
					afterHide && afterHide( me._get( 'container' ), me );

					me._set( 'stateShown', false );

				},

				show: function ( me ) {

					if ( me._get( 'stateShown' ) ) 
						return;
					
					// 状态恢复
					_restore.call( me );

					// 显示前的回调
					var beforeShown = me._get( 'beforeShown' );
					beforeShown && beforeShown( me._get( 'container' ), me );
					
					me._get( 'targetPanel' ).show();

					me._set( 'stateShown', true );

				},

				change: function ( me, old ) {

					var value = me._get( 'value' );

					// 改变显示文字
					me._get( 'targetShown' ).val( value );
					//改变城市的value;
					me._get( 'basicCity' ).val( value );
					var onchange = me._get( 'onchange' );
					value != old && onchange && onchange( value, me._get( 'container' ) );
					
					_triggers.hide( me );

				}

			};

			var _restore = function () {

				var me = this;
				//me._get('targetHotCityUl').html(CONSTANTS.hotCitys);
				// remove active		
				me._get( 'targetProvinces' ).removeClass( CLASSES.active );
				me._get( 'targetCityObjs' )
					.removeClass( CLASSES.active )					

				me._set('value',me._get('targetShown').val());

				//通过表示控制流程
				var flag = true;

				// 当前值的选中状态
				var value = me._get( 'value' );
				me._get('targetCityUl').hide();
				if ( value  == '' || value == '所在城市'){
					me._get('targetHotCityLeft').addClass(CLASSES.active);
					
					me._get('targetHotCityLeft').next().show();
					me._get('targetProvinceWrap').animate({
								scrollTop:0+"px"
					},1);	
					flag = false;
					return false;
				}
				if(!flag){
					return ;
				}
				// 热门城市
				 me._get('targetHotCitys').each(function(){
				 	var _this = $(this);
				 	var _city = $.trim(_this.text());
				 	if(value == _city){
				 		
				 		me._get('targetHotCityUl').show();
				 		_this.addClass(CLASSES.active );
				 		me._get('targetHotCityLeft').addClass(CLASSES.active );
				 		
						me._get('targetProvinceWrap').animate({
								scrollTop:0+"px"
							},1);				 		
				 		flag = false;
				 		return  false;
				 	}
				 });
				 if(!flag){
					return ;
				}
				 
				//非热门城市
				me._get('targetCityWrap').each(function(){

					var _this = $(this);

					_this.find("li").each(function(){
						var thisCity = $(this);
						if(value == $.trim(thisCity.text())){
							var oUl = thisCity.parent();
							oUl.show();
							thisCity.addClass(CLASSES.active );
							oUl.prev().addClass(CLASSES.active );
							var topPx = oUl.parent().index();
						
							me._get('targetProvinceWrap').animate({
								scrollTop:(topPx)*33+"px"
							},1);
							flag = false;
							return false;
						}
					});
				});
				 if(!flag){
					return ;
				}

			};

			// 代理处理
			var _delegates = {

				// 容器点击
				containerClick: function ( event, me ) {

					me._get( 'stateShown' )
						? _triggers.hide( me )
						: _triggers.show( me );

				},
				documentClick: function ( event, me ) {

					me._get( 'stateShown' ) && _triggers.hide( me );

				},

				// 省份点击
				provinceClick: function ( event, me ) {

					var target = $( this );

					var text = $.trim( target.text() );

					// 点击					
					me._get( 'targetProvinces' ).removeClass( CLASSES.active );
					target.addClass( CLASSES.active );
					
					me._get('targetCityWrap').hide();
					target.next().show();
					
					me._get( 'targetCityObjs' )
						.removeClass( CLASSES.active );

					target.next().find("li").each(function(){
						var _this = $(this);
						if(me._get('value') == $.trim(_this.text())){
							_this.addClass( CLASSES.active );
						}
					});
				},

				// 城市点击
				cityClick: function ( event, me ) {

					var target = $( this );
					var targetParent = target.parent();
					targetParent.find("li").removeClass( CLASSES.active );
					target.addClass( CLASSES.active );

					var old = me._get( 'value' );
					me._set( 'value', target.text());
					_triggers.change( me, old );

				}

			};

			var _bindEvents = function () {

				var me = this;

				// 点击父元素显示容器
				me._get( 'container' ).bind( 'click', function ( event ) {
					
					_delegates.containerClick.call( this, event, me );
					event.stopPropagation();
				} );
				// 与之配对，document的click
				$( document ).bind( 'click', function ( event ) {
					_delegates.documentClick.call( this, event, me );
				} );

				me._get( 'targetPanel' ).bind( 'click', function ( event ) {
					event.stopPropagation();
				} );

				// 点击省份
				me._get( 'targetProvinceWrap' ).delegate( 'span', 'click', function ( event ) {
					_delegates.provinceClick.call( this, event, me );
					event.stopPropagation();
				} );

				// 点击城市
				me._get( 'targetCityWrap' ).delegate( 'li', 'click', function ( event ) {
					_delegates.cityClick.call( this, event, me );
					event.stopPropagation();
				} );

			};

			CityWrapperConstructor.prototype = {

				constructor: CityWrapperConstructor,

				// 原型方法
				show: function () {

					var me = this;

					_triggers.show( me );

					return me;

				},

				hide: function () {

					var me = this;

					_triggers.hide( me );

					return me;

				},

				/**
				 * 设置值
				 * @param string value 例如 12.1
				 * @param boolean triggerChange 是否出发change事件，默认不触发
				 */
				set: function ( value, triggerChange ) {

					var me =  this;

					var old = me._get( 'value' );
					me._set( 'value', value );
					// 改变显示文字
					me._get( 'targetShown' ).val( value );

					if ( triggerChange ) {
						var onchange = me._get( 'onchange' );
						value != old && onchange && onchange( value, me._get( 'container' ) );
					}

					return me;

				},

				get: function () {

					var me = this;

					return me._get( 'value' );

				},

				/**
				 * 置空
				 * @return {[type]} [description]
				 */
				reset: function () {

					var me = this;

					me.set( '' );

					return me;

				}			

			};

			return CityWrapperConstructor;			
		}(),

		// 日历包装器
		CalendarWrapper: function () {

			// 静态、私有
			var CLASSES = {
				// 激活状态
				active: 'active',
				// 今天
				today: 'today',
				// hover
				hover: 'active',
				//disable
				disable: 'disable'
			};

			// 静态、私有、常量
			var CONSTANTS = {
				toToday: '<li>至今</li>',
				toTodayText: '至今'
			};

			var CalendarWrapperConstructor = function ( opt ) {

				// 私有属性池
				var _pool = {  };

				// 私有方法 _get 和 _set【此两个方法不应该从外部调用】
				this._get = function ( key ) {
					return _pool[ key ];
				};
				this._set = function ( key, value ) {
					var me = this;
					_pool[ key ] = value;
					return me;
				};

				// 初始化
				_init.call( this, opt );

			};

			// 私有方法，初始化方法也是对外不暴露了
			var _init = function ( opt ) {

				var me = this;

				if ( !opt.container )
					throw 'Need options.container!';

				// 从参数设置一系列属性值
				// 设置父容器
				me._set( 'container', opt.container )
					._set( 'onchange', opt.onchange )
					._set( 'beforeShown', opt.beforeShown )
					._set( 'afterHide', opt.afterHide )
					// 默认是不显示“至今”的
					._set( 'has2Today', opt.has2Today || false )
					// 获取自身的各种操作对象
					// 隐藏域
					._set( 'targetHidden', opt.container.find( 'input[type="hidden"]' ) )
					// 显示区域的button input
					._set( 'targetShown', opt.container.find( 'input[type="button"]' ) )
					// dateContainer
					._set( 'targetPanel', opt.container.find( '.mr_calendar_ym' ) )
					// yearsContainer
					._set( 'targetYearsCon', opt.container.find( '.mr_year' ) )
					._set( 'targetMonthsCon', opt.container.find( '.mr_month' ) )
					// li and span
					._set( 'targetYearsLis', opt.container.find( '.mr_year li' ) )
					._set( 'targetMonthsLis', opt.container.find( '.mr_month li' ) )
					._set( 'targetMonthsSpans', opt.container.find( '.mr_month span' ) )
					// 自身的值，已经执行选择的话，这个是有值的，例如 2014.20或者“至今”两个字
					._set( 'value', undefined )
					// 自身值，年
					._set( 'valueYear', undefined )
					// 自身值，月
					._set( 'valueMonth', undefined )
					// 显示状态 true / false
					._set( 'stateShown', false )
					// 左边界
					._set( 'leftBr', undefined )
					// 右边界
					._set( 'rightBr', undefined )
					._set('targetYearWrap',opt.container.find( '.mr_year' ) )
					// 是否禁用
					._set( 'disable', false );

				// 初始化动作
				_behaver.call( me );

			};

			// 适配器
			var _monthAdapter = function ( month ) {
				return month + '月';
			};

			var _monthUnAdapter = function ( monthText ) {
				return monthText.replace( '月', '' );
			};

			var _valueAdapter = function ( year, month ) {
				// 对只有个位数字的月份，加前置0
				if ( + month < 10 ) {
					month = '0' + month;
				}
				return year + '.' + month;
			};

			var _valueUnAdapter = function ( value ) {
				value += '';
				var result = value.split( '.' );
				return {
					year: result[ 0 ],
					month: parseInt( result[ 1 ] ) + ''
				};
			};

			var _behaver = function () {

				var me = this;

				// 检查是否有“至今”
				me._get( 'has2Today' ) 
					&& me._get( 'targetYearsCon' ).prepend( CONSTANTS.toToday )
					// 更新年份的li缓存，因为添加了至今
					&& me._set( 'targetYearsLis', me._get( 'container' ).find( '.mr_year li' ) );

				// 获取现在的日期，并且存储
				_getToday.call( me );

				// 为现在的日期项添加对应的class
				_patchTodayCls.call( me );

				// 事件绑定
				_bindEvents.call( me );

			};

			// 触发器
			var _triggers = {

				hide: function ( me ) {

					if ( !me._get( 'stateShown' ) ) 
						return;

					me._get( 'targetPanel' ).hide();
					// 隐藏后的回调
					var afterHide = me._get( 'afterHide' );
					afterHide && afterHide( me._get( 'container' ), me );

					me._set( 'stateShown', false );

					// clear
					me._set( 'valueYear', undefined )
						._set( 'valueMonth', undefined );


				},

				show: function ( me ) {

					if ( me._get( 'disable' ) )
						return;

					if ( me._get( 'stateShown' ) ) 
						return;

					// 显示之前，手动触发document.click
					//top.document.body.click();

					// 状态恢复
					_restore.call( me );

					// 显示前的回调
					var beforeShown = me._get( 'beforeShown' );
					beforeShown && beforeShown( me._get( 'container' ), me );
					me._get( 'targetPanel' ).show();

					me._set( 'stateShown', true );

				},

				change: function ( me, old ) {

					var value = me._get( 'value' );

					// 改变显示文字
					me._get( 'targetHidden' ).val( value );
					me._get( 'targetShown' ).val( value );

					var onchange = me._get( 'onchange' );
					value != old && onchange && onchange( value, me._get( 'container' ) );

					_triggers.hide( me );

				}

			};

			var _restore = function () {

				var me = this;

				// remove active
				me._get( 'targetYearsLis' ).removeClass( CLASSES.active );
				me._get( 'targetMonthsSpans' )
					.removeClass( CLASSES.active )
					.removeClass( CLASSES.disable );

				// 设置边界
				_setBoundaries.call( me );

				// 当前值的选中状态
				var value = me._get( 'value' );

				//日历控件年列表第一个年份
				var _theFirstYear = me._get( 'targetYearsCon' ).find( 'li:first' );
			
				//如果默认为空
				if($.trim(value) == ""){
					var activeObject;
					if($.trim(_theFirstYear.text()) == "至今"){
						var a = _theFirstYear.nextAll().not(".disable").first();
						//a.addClass( CLASSES.active );
						a.trigger("click");
						me._set( 'valueYear', a.text() );
						activeObject = a;
					}else{
						var b = me._get( 'targetYearsCon').find("li").not(".disable").first();
						//b.addClass( CLASSES.active );
						b.trigger("click");
						me._set( 'valueYear', b.text() );
						activeObject = b;
					}
					me._get('targetYearWrap').animate({
						scrollTop:$(b).index()*32+"px"
					},1);
					return;
				}
				
				// “至今”
				if ( value == CONSTANTS.toTodayText ) {
					// 第一个就是“至今”
					me._get( 'targetYearsCon' ).find( 'li:first' ).addClass( CLASSES.active );
					// 选择本月
					var todayMonth = me._get( 'todayMonth' );
					me._get( 'targetMonthsSpans' ).each( function () {
						var target = $( this );
						if ( $.trim( target.text() ) == _monthAdapter( todayMonth ) ) {
							target.addClass( CLASSES.today );
						}
					} );
					me._get('targetYearWrap').animate({
						scrollTop:0
					},1);
					return;
				}

				// 非“至今”，选中年和月
				var valueObj = _valueUnAdapter( value );
				// 恢复年月记录
				me._set( 'valueYear', valueObj.year );
				me._set( 'valueMonth', valueObj.month );
				// 选年
				me._get( 'targetYearsLis' ).each( function () {
					var target = $( this );
					if ( $.trim( target.text() ) == valueObj.year ) {
						target.addClass( CLASSES.active );
						// 在使用set的时候，解决自动选中今天月份的小bug
						window.setTimeout( function () {
							target.trigger( 'click' );
						}, 0 );
						me._get('targetYearWrap').animate({
							scrollTop:target.index()*32+"px"
						},1);
						return false;
					}
				} );
				
				// 选月
				me._get( 'targetMonthsSpans' ).each( function () {
					var target = $( this );
					if ( $.trim( target.text() ) == _monthAdapter( valueObj.month ) ) {
						target.addClass( CLASSES.active );
						return false;
					}
				} );

			};

			// 代理处理
			var _delegates = {

				// 容器点击
				containerClick: function ( event, me ) {

					me._get( 'stateShown' )
						? _triggers.hide( me )
						: _triggers.show( me );

				},
				documentClick: function ( event, me ) {

					me._get( 'stateShown' ) && _triggers.hide( me );

				},

				// 年份点击
				yearsClick: function ( event, me ) {

					var target = $( this );

					// 如果超出范围
					if ( target.hasClass( CLASSES.disable ) )
						return;

					var text = $.trim( target.text() );

					// 如果点击的“至今”
					if ( text == CONSTANTS.toTodayText ) {
						var old = me._get( 'value' );
						me._set( 'value', text );
						_triggers.change( me, old );
						return;
					}

					// 非“至今”的点击
					me._set( 'valueYear', text );
					me._get( 'targetYearsLis' ).removeClass( CLASSES.active );
					target.addClass( CLASSES.active );
					// then等待选择月份

					// 清理月份状态
					// me._set( 'valueMonth', undefined );
					me._get( 'targetMonthsSpans' )
						.removeClass( CLASSES.active )
						.removeClass( CLASSES.today )
						.removeClass( CLASSES.disable );

					// 判断如果是选择的今年，再把今年的这个月份选中
					if ( text == me._get( 'todayYear' ) ) {
						var todayMonth = me._get( 'todayMonth' );
						me._get( 'targetMonthsSpans' ).each( function () {
							var target = $( this );
							if ( $.trim( target.text() ) == _monthAdapter( todayMonth ) ) {
								target.addClass( CLASSES.today );
							}
						} );
					}

					// 设置边界
					_setBoundaries.call( me );

					// 判断如果是回到已选的年月，再把已选的年月选中
					var now = me._get( 'value' );
					if ( !now )
						return;
					now = _valueUnAdapter( now );
					if ( text == now.year ) {
						me._get( 'targetMonthsSpans' ).each( function () {
							var target = $( this );
							if ( $.trim( target.text() ) == _monthAdapter( now.month ) ) {
								target.addClass( CLASSES.active );
							}
						} );
					}

				},

				// 月份点击
				monthsClick: function ( event, me ) {

					var year;
					// 如果没选择年份(包括选择“至今”)，则不响应
					if ( ! ( year = me._get( 'valueYear' ) ) ) 
						return;
					var target = $( this );
					// 如果超出范围
					if ( target.hasClass( CLASSES.disable ) )
						return;

					var text = _monthUnAdapter( $.trim( target.text() ) );

					me._set( 'valueMonth', text );
					me._get( 'targetMonthsSpans' ).removeClass( CLASSES.active );
					target.addClass( CLASSES.active );

					var old = me._get( 'value' );
					me._set( 'value', _valueAdapter( year, text ) );
					_triggers.change( me, old );

				}

			};

			var _bindEvents = function () {

				var me = this;

				// 点击父元素显示容器
				me._get( 'container' ).bind( 'click', function ( event ) {
					_delegates.containerClick.call( this, event, me );
					event.stopPropagation();
				} );
				// 与之配对，document的click
				$( document ).bind( 'click', function ( event ) {
					_delegates.documentClick.call( this, event, me );
				} );

				me._get( 'targetPanel' ).bind( 'click', function ( event ) {
					event.stopPropagation();
				} );

				// 点击年份
				me._get( 'targetYearsCon' ).delegate( 'li', 'click', function ( event ) {
					_delegates.yearsClick.call( this, event, me );
					event.stopPropagation();
				} );

				// 点击月份
				me._get( 'targetMonthsCon' ).delegate( 'span', 'click', function ( event ) {
					_delegates.monthsClick.call( this, event, me );
					event.stopPropagation();
				} );

			};

			var _setMonthBoundary = function ( calljudge ) {

				var me = this;

				me._get( 'targetMonthsSpans' ).each( function () {
					var target = $( this );
					if ( calljudge( _monthUnAdapter( $.trim( target.text() ) ) ) ) {
						target.addClass( CLASSES.disable );
					}
				} );

			};

			var _setYearBoundary = function ( calljudge ) {

				var me = this;

				me._get( 'targetYearsLis' ).each( function () {
					var target = $( this );
					if ( calljudge( $.trim( target.text() ) ) ) {
						target.addClass( CLASSES.disable );
					}
				} );

			};

			// 设置边界
			var _setBoundaries = function () {

				var me = this;

				me._get( 'targetMonthsSpans' ).removeClass( CLASSES.disable );
				me._get( 'targetYearsLis' ).removeClass( CLASSES.disable );

				var leftBr = me._get( 'leftBr' );
				var rightBr = me._get( 'rightBr' );
				var value = me._get( 'value' );
				var year;

				// “至今”
				if ( rightBr == CONSTANTS.toTodayText || !rightBr ) {
					rightBr = _valueAdapter( me._get( 'todayYear' ), me._get( 'todayMonth' ) );
				}

				// 第一次打开
				if ( !value && !me._get( 'valueYear' ) ) {
					// var todayMonth = me._get( 'todayMonth' );
					// _setMonthBoundary.call( me, function ( value ) {
					// 	return value > todayMonth;
					// } );
					year = me._get( 'todayYear' );
					_handleBoundaries.call( me, leftBr, rightBr, year );
					return;
				}

				// 日历打开，并且已经记录值（已选），恢复已选状态的动作
				if ( value && !me._get( 'valueYear' ) ) {
					year = value == CONSTANTS.toTodayText
						? me._get( 'todayYear' )
						: _valueUnAdapter( value ).year;
				} else {
					// 否则就是显示以后的点选某一年的动作
					year = me._get( 'valueYear' );
				}

				_handleBoundaries.call( me, leftBr, rightBr, year );

			};

			var _handleBoundaries = function ( leftBr, rightBr, year ) {

				var me = this;

				if ( leftBr ) {
					var leftBrObj = _valueUnAdapter( leftBr );
					year == leftBrObj.year && _setMonthBoundary.call( me, function ( value ) {
						return ( + value ) <  ( + leftBrObj.month );
					} );
					// 如果年份小于leftBtObj则全部置灰前面的年份
					_setYearBoundary.call( me, function ( value ) {
						return ( + value ) < ( leftBrObj.year );
					} );
				}

				if ( rightBr ) {
					var rightBrObj = _valueUnAdapter( rightBr );
					year == rightBrObj.year && _setMonthBoundary.call( me, function ( value ) {
						return ( + value ) > ( + rightBrObj.month );
					} );
					year > rightBrObj.year && _setMonthBoundary.call( me, function ( value ) {
						return true;
					} );
					// 如果年份大于rightBtObj则全部置灰后面的年份
					_setYearBoundary.call( me, function ( value ) {
						return ( + value ) > ( rightBrObj.year );
					} );
				}

			};

			var _patchTodayCls = function () {

				var me = this;
				var year = me._get( 'todayYear' );
				var month = me._get( 'todayMonth' );
				me._get( 'targetYearsLis' ).each( function () {
					var item = $( this );
					if ( $.trim( item.text() ) == year ) {
						item.addClass( CLASSES.today );
						// 跳出循环
						return false;
					}
				} );
				me._get( 'targetMonthsSpans' ).each( function () {
					var item = $( this );
					if ( $.trim( item.text() ) == _monthAdapter( month ) ) {
						item.addClass( CLASSES.today );
						// 跳出循环
						return false;
					}
				} );

			};

			var _getToday = function () {

				var me = this;
				var date = new Date();

				var year = date.getFullYear();
				var month = date.getMonth() + 1;

				// 得到并且存储
				me._set( 'todayYear', year );
				me._set( 'todayMonth', month );

				var todayDate = _valueAdapter( year, month );
				me._set( 'todayDate', todayDate );

				// 设置右边界，默认为今天的日期
				me._set( 'rightBr', todayDate );

			};

			CalendarWrapperConstructor.prototype = {

				constructor: CalendarWrapperConstructor,

				// 原型方法
				show: function () {

					var me = this;

					_triggers.show( me );

					return me;

				},

				hide: function () {

					var me = this;

					_triggers.hide( me );

					return me;

				},

				disable: function () {

					var me = this;

					me._set( 'disable', true );

					return me;

				},

				enable: function () {

					var me = this;

					me._set( 'disable', false );

					return me;

				},

				/**
				 * 设置值
				 * @param string value 例如 12.1
				 * @param boolean triggerChange 是否出发change事件，默认不触发
				 */
				set: function ( value, triggerChange ) {

					var me =  this;

					var old = me._get( 'value' );
					me._set( 'value', value );
					// 改变显示文字
					me._get( 'targetHidden' ).val( value );
					me._get( 'targetShown' ).val( value );

					if ( triggerChange ) {
						var onchange = me._get( 'onchange' );
						value != old && onchange && onchange( value, me._get( 'container' ) );
					}

					return me;

				},

				get: function () {

					var me = this;

					return me._get( 'value' );

				},

				/**
				 * 置空
				 * @return {[type]} [description]
				 */
				reset: function () {

					var me = this;

					me.set( '' );

					// 清除边界
					me._set( 'leftBr', undefined );
					me._set( 'rightBr', undefined );

					return me;

				},

				/**
				 * 设置左边界【包含设置的值】
				 * @param string leftValue 例如 2012.10
				 */
				setLeftBoundary: function ( leftValue ) {

					var me = this;

					me._set( 'leftBr', leftValue );
					_setBoundaries.call( me );

					return me;

				},

				/**
				 * 设置右边界【包含设置的值】
				 * @param string leftValue 例如 2014.6
				 */
				setRightBoundary: function ( rightValue ) {

					var me = this;

					me._set( 'rightBr', rightValue );
					_setBoundaries.call( me );

					return me;

				}

			};

			return CalendarWrapperConstructor;

		}(),
		//双日历选择
		CalendarDouble: function (){
			
			var CalendarDoubleConstructor = function( opt ){

				this.init(opt); 
			};
			
			CalendarDoubleConstructor.prototype = {

				constructor: CalendarDoubleConstructor,

				init: function ( opt ){
					var me = this;
					if ( !opt.wrap ){
						throw 'Need wrap!';
					}
					me.wrap = opt.wrap;
					var startDate = me.wrap.children('div:first');
					var endDate = me.wrap.children('div:last');
					//设置开始时间的对象
					me.calendarStart = new components.CalendarWrapper({

						container: startDate ,
			            onchange: function(value,container) {
			            	//设置结束时间
			                me.calendarEnd.setLeftBoundary(value);
			            },
			            beforeShown: function(container, instance) {
							//设置结束时间
			            	$( document ).trigger("click");
			            	var button = container.find('input[type="button"]');
			        		if( button.val() != "起始时间" && button.val() != "结束时间"  ){
			        			container.find('span.error').hide();
			        		}
			            	me.calendarEnd.hide();
			            	container.siblings().find('input[type="button"]').removeClass('select_color');
			            	container.parents('.practice').find('.practiceDate').find('input[type="button"]').removeClass('select_color');
			            	container.parents('.work').find('.workDate').find('input[type="button"]').removeClass('select_color');
			            	container.find('input[type="button"]').addClass('select_color');
			            	//me.opt.beforeStartShown && me.opt.beforeStartShown(value);
			            },
			            afterHide: function(container, instance) {
			            	container.find('input[type="button"]').removeClass('select_color');
							$('.mr_workexpFormTop').css('paddingBottom','15px').siblings('.adddel').css('marginTop','0px');
			        		$('.mr_practiceConFormTop').css('paddingBottom','15px').siblings('.adddel').css('marginTop','0px');
			        		var button = container.find('input[type="button"]');
			        		if( button.val() != "起始时间" && button.val() != "结束时间"  ){
			        			container.find('input[type="button"]').css('color','#333');
			        			container.find('span.error').hide();
			        		}
			        		
			            }

					});
					//设置结束时间的对象

					me.calendarEnd = new components.CalendarWrapper({
						container: endDate ,
						onchange: function(value,container) {
			            	//设置开始时间
							me.calendarStart.setRightBoundary(value);
				        },
			            beforeShown: function(container, instance) {
							//设置开始时间
			            	$( document ).trigger("click");
			            	var button = container.find('input[type="button"]');
			        		if( button.val() != "起始时间" && button.val() != "结束时间"  ){
			        			container.find('span.error').hide();
			        		}
			            	me.calendarStart.hide();
			            	container.siblings().find('input[type="button"]').removeClass('select_color');
			            	container.parents('.practice').find('.practiceDate').find('input[type="button"]').removeClass('select_color');
			            	container.parents('.work').find('.workDate').find('input[type="button"]').removeClass('select_color');
			            	container.find('input[type="button"]').addClass('select_color');
			            },
			            afterHide: function(container, instance) {
			            	container.find('input[type="button"]').removeClass('select_color');
							$('.mr_workexpFormTop').css('paddingBottom','15px').siblings('.adddel').css('marginTop','0px');
			        		$('.mr_practiceConFormTop').css('paddingBottom','15px').siblings('.adddel').css('marginTop','0px');
			        		var button = container.find('input[type="button"]');
			        		if( button.val() != "起始时间" && button.val() != "结束时间"  ){
			        			container.find('input[type="button"]').css('color','#333');
			        			container.find('span.error').hide();
			        		}
							
			            },
			            has2Today: true

					});
				},
				setCalendarStart: function( value ){
					var me = this;
					 me.calendarStart.set( value , true );
				},
				setCalendarEnd: function( value ){
					var me = this;
					 me.calendarEnd.set( value , true );
				},
				getCalendarStart: function(){
					var me = this;
					return me.calendarStart.get();
				},
				getCalendarEnd: function(){
					var me = this;
					return me.calendarEnd.get();
				},
				disable: function(){
					var me = this;
					me.calendarStart.disable();
					me.calendarEnd.disable();

				},
				enable: function(){
					var me = this;
					me.calendarStart.enable();
					me.calendarEnd.enable();
				},
				// 原型方法
				show: function () {
					var me = this;
					me.calendarStart.show();
					me.calendarEnd.show();

				},

				hide: function () {
					var me = this;
					me.calendarStart.hide();
					me.calendarEnd.hide();

				}

			}
			return CalendarDoubleConstructor;
		}()

	};
	// 暴露
	window.components = components;

} )();