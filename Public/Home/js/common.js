/**
 * @author pooky
 * @description common
 */

window.myresumeCommon = window.myresumeCommon || {

	// 请求路径
	requestTargets: {
		// 作品的增、改
		workShowSave: '/workShow/save.json',
		// 作品的删
		workShowDel: '/workShow/delws.json',

		// 技能增、改
		skillSave: '/skillEvaluate/save.json',
		// 技能删
		skillDel: '/skillEvaluate/delSkill.json',
		// 技能全删
		skillDelAll: '/skillEvaluate/delAllSkill.json',

		// 作品图片
		workUpload: '/workShow/uploadWorkPic.json',

		// 作品剪切
		workCut: '/workShow/cutWorkPic.json',

		// 项目经验 save、edit
		projectExpSave: '/projectExperience/save.json',

		// 项目经验 del
		projectExpDel: '/projectExperience/delProject.json',

		// 期望工作
		expectJobSave: '/expectJobs/expectJobs.json',

		// 自我描述
		myRemark: '/resume/intro.json',

		//上传公司logo
		uploadLogo: '/workExperience/uploadLogo.json',

		//上传头像
		photoUpload : '/resume/uploadPhoto.json'
			
	},

	// 渲染模板
	templates: {

		workShowOnline: [
			'<div class="mr_wo_show" data-type="#{type}" data-id="#{id}">',
				'<div>',
					'<div class="mr_edit mr_c_r_t">',
						'<i></i><em class="mr_edit_text" data-type="#{type}">编辑</em>',
					'</div>',
				'</div>',
				'<div class="mr_self_site">',
					'<a class="mr_self_sitelink" href="#{ahref}" target="_blank">',
					'#{href}',
					'</a>',
				'</div>',
				'<div class="mr_wo_preview">',
					'#{desc}',
				'</div>',
			'</div>'
		].join( '' ),

		workShowUpload: [
			'<div class="mr_wu_show" data-type="#{type}" data-id="#{id}">',
				'<div class="mr_wu_t">',
					'<a href="'+ctx+'/#{imageUrl}" target="_blank">',
						'<img class="wh43" src="'+ctx+'/#{imgsrc}" alt="#{title}" data-origin-src="#{imageUrl}">',
					'</a>',
				'</div>',
				'<div class="mr_wu_con">',
					'<div class="clearfixs">',
						'<div class="mr_content_l #{hasTitle}">',
							'<div class="l2 maxWidth">',
								'<span class="mr_work_title">[ #{title} ]</span>',
							'</div>',
						'</div>',
						'<div class="mr_content_r">',
							'<div class="mr_edit mr_c_r_t">',
								'<i></i><em class="mr_edit_text" data-type="#{type}">编辑</em>',
							'</div>',
						'</div>',
					'</div>	',
					'<div class="mr_wu_con_m">',
						'#{desc}',
					'</div>	',
				'</div>',
			'</div>'
		].join( '' ),

		skillItem: [
			'<div class="mr_skill_con" data-grade="#{skillPercent}" data-skill-id="#{id}">',
				'<span class="mr_skill_name" title="#{skillName}">#{skillName}</span>',
				'<span class="mr_skill_plan">',
					'<em></em>',
				'</span>',
				'<span class="mr_skill_delete"></span>',
				'<span class="mr_skill_level">#{masterLevel}</span>',
				'<i class="mr_skill_circle"><em>#{masterLevel}</em></i>',
			'</div>'
		].join( '' )

	},

    /**
     * 默认配置
     */
    config: {

    	// 用户上传头像的selector的大小
    	userPhotoSelector: {
    		width: 250,
    		height: 250
    	},

    	// 用户上传作品，比例为4:3
    	workShowSelector: {
    		width: 280,
    		height: 210
    	},

    	cutImage: {
		    width: 360,
		    height: 360,
		    bgColor: '#ccc',
		    enableRotation: false,
		    enableZoom: true,
		    selector: {
		        w: 250,
		        h: 250,
		        showPositionsOnDrag: false,
		        showDimetionsOnDrag: false,
		        centered: true,
		        bgInfoLayer: '#fff',
		        borderColor: '#02d1a1',
		        animated: false,
		        maxWidth: 358,
		        maxHeight: 358,
		        borderColorHover: '#02d1a1'
		    },
		    image: {
		        source: '',
		        // 在上传完图片后，这个必须要设置，width height
		        width: 0,
		        height: 0,
		        minZoom: 10,
		        maxZoom: 300
		    }
		},
    	// 编辑器配置
        tinymce: {
            // Location of TinyMCE script
            script_url: ctx + '/js/tinymce/jscripts/tiny_mce/tiny_mce.js',

            // General options
            theme: "advanced",
            language: "zh-cn",
            plugins: "paste,autolink,lists,style,layer,save,advhr,advimage,advlink,iespell,inlinepopups,preview,media,searchreplace,contextmenu,fullscreen,noneditable,visualchars,nonbreaking",

            // Theme options
            theme_advanced_buttons1: "bullist,numlist",
            theme_advanced_toolbar_location: "top",
            theme_advanced_toolbar_align: "left",
            theme_advanced_statusbar_location: "none", //定义输入框下方是否显示状态栏，默认不显示
            theme_advanced_resizing: false,
            paste_auto_cleanup_on_paste: true,
            paste_as_text: true,
            auto_cleanup_word: true,
            paste_remove_styles: true,
            contextmenu: "copy cut paste",
            force_br_newlines: true,
            force_p_newlines: false,
            apply_source_formatting: false,
            remove_linebreaks: false,
            convert_newlines_to_brs: true,

            // Example content CSS (should be your site CSS)
            content_css: ctx + "/js/tinymce/examples/css/content.css",

            // Drop lists for link/image/media/template dialogs
            template_external_list_url: "lists/template_list.js",
            external_link_list_url: "lists/link_list.js",

            // Replace values for the template plugin
            template_replace_values: {
                username: "Some User",
                staffid: "991234"
            },
            onchange_callback: function(editor) {
                tinyMCE.triggerSave();
                var editorContent = tinyMCE.get(editor.id).getContent();
                if (editorContent.length > 20) {
                    $("#" + editor.id).valid();
                }
                /* alert($("#" + editor.id).width());*/
            }
        }

    },

	// 一些工具方法
	utils: {

		imageUpload: function ( targetInput, targetUrl, success, fail ) {

			targetInput = $( targetInput );
			var inputId = targetInput.attr( 'id' );
			var hint = targetInput.attr( 'title' );

			// var dataType = 'json';
			var dataType = 'text';

			var params = { };

			this.AllowExt = '.jpg,.gif,.jpeg,.png,.pjpeg';
			this.FileExt = targetInput.val().substr(targetInput.val().lastIndexOf(".")).toLowerCase();
			if(this.AllowExt != 0 && this.AllowExt.indexOf(this.FileExt) == -1)//judge file format
			{
				errorTips( hint );
				$("input[type = 'file']").val("");
			}else{
				$.ajaxFileUpload ({
					url: targetUrl,
					secureuri: false,
					fileElementId: inputId,
					data: params,
					dataType: dataType,
					// "content":{"srcImageW":650,"srcImageH":346,"uploadPath":"upload/workPic/d7f6a8cb0fd5473193a3ffd021a54838.png"}
					success: function ( rs ) {
						if ( dataType == 'text' )
							rs = $.parseJSON( rs );
						if( rs.success ){
							success && success( rs.content, inputId );
						}
						else{
							fail && fail( 1 );
							errorTips( hint , "上传" );
						}
					},
					error:function(data){
						fail && fail( data );
						errorTips( "上传失败，请重新上传","上传");
					}
				});
			}

		},

		unset: function ( unsets ) {
			$.each( unsets, function ( index, item ) {
				unsets[ index ] = null;
			 } );
		},

		/**
		 * 对目标字符串进行格式化
		 * @name baidu.string.format
		 * @function
		 * @grammar baidu.string.format(source, opts)
		 * @param {string} source 目标字符串
		 * @param {Object|string...} opts 提供相应数据的对象或多个字符串
		 * @remark
		 *
		 * @shortcut format
		 * @meta standard
		 *
		 * opts参数为“Object”时，替换目标字符串中的#{property name}部分。<br>
		 * opts为“string...”时，替换目标字符串中的#{0}、#{1}...部分。
		 *
		 * @returns {string} 格式化后的字符串
		 */
		strFormat: function (source, opts) {
		    source = String(source);
		    var data = Array.prototype.slice.call(arguments,1), toString = Object.prototype.toString;
		    if(data.length){
			    data = data.length == 1 ?
			    	/* ie 下 Object.prototype.toString.call(null) == '[object Object]' */
			    	(opts !== null && (/\[object Array\]|\[object Object\]/.test(toString.call(opts))) ? opts : data)
			    	: data;
		    	return source.replace(/#\{(.+?)\}/g, function (match, key){
			    	var replacer = data[key];
			    	// chrome 下 typeof /a/ == 'function'
			    	if('[object Function]' == toString.call(replacer)){
			    		replacer = replacer(key);
			    	}
			    	return ('undefined' == typeof replacer ? '' : replacer);
		    	});
		    }
		    return source;
		},

		// 请求器
		requester: function ( params, callback ) {
			params.dataType = params.dataType || 'json';
			params.type     = params.type || 'POST';
			params.data     = params.data || {};
			// 放置token
			// params.data.resubmitToken = $.trim( $( '#resubmitToken' ).val() );
			params.data.resubmitToken = globals.token;
			$.ajax( params ).done( function ( response ) {
				// 设置token
				if( null != response.resubmitToken && '' != response.resubmitToken ){
					globals.token = response.resubmitToken;
				}
				callback && callback( response );
			} );
		},

		/**
		 * 增加'http://'的url前缀
		 * @param {string} prefixes 需要增加的前缀
		 * @param {string} v        原值
		 * @param {function} set    回调函数
		 * @use   x.addHttpPrefix('http://|https://', 'www.lagou.com', function(newV){
	              		me.setValue(newV);
	              });
		 */
		addHttpPrefix: function(prefixes, v, set){
			prefixes = prefixes.split('|');
			var defaultPrefix = prefixes[0];
			for(var i = 0, len = prefixes.length; i < len; i ++){
				if(prefixes[i] === v.substring(0, prefixes[i].length))
					return;
			}
			set(defaultPrefix + v);
		},

		/**
		 * 显示错误提示，并且自动隐藏
		 * @param  {object} target 目标元素
		 * @param  {string} hint     提示语
		 * @param  {number} delay    默认2000
		 */
		errorTips: function ( target, hint, delay ) {

			// var target = $( selector );
			delay = delay || 2000;
			
			if ( target.data( 'errortipspending' ) == 1 )
				return;

			// 显示
			target.text( hint );
			target.show();
			target.data( 'errortipspending', 1 );
			window.setTimeout( function () {
				target.hide();
				target.data( 'errortipspending', 0 );
			}, delay );

		},

	    /**
	     * 创建节流函数（即，一个函数可能在短时间内执行好几遍，为了
	     * 节约性能，这个函数可以解决这个问题，例如onscroll事件的触发等等）
	     * 
	     * @param {Function} method 需要节流的函数 
	     * @param {Array} args 传入参数列表
	     * @param {Object} context 执行上线文
	     * @param {Number} delay 执行delay
	     * @return {undefined}
	     */
	    throttle: function( method, args, context, delay ) {
	        context = context == undefined ? null : context;
	        method.tId && clearTimeout(method.tId);
	        method.tId = setTimeout(function() {
	            method.apply(context, args);
	        }, (delay ? delay : 140));
	    },

	    /**
	     * 实时监听input输入框的值变化
	     * 
	     * @param {HTMLElement} input 需要监听的input元素, jQuery包装后的元素
	     * @param {Function} callback 回调函数，会把当前值传入
	     * @return {undefined}
	     */
	    inputerListener: function( input, callback ) {
	    	var delay = 0;
	        if("onpropertychange" in input[0] 
	        	&& ( $.browser.ie && (parseInt( $.browser.version <= 8 )) ) ) { //ie7,8完美支持，ie9不支持
	        	input.bind( 'propertychange', function(e) {
                    e.originalEvent.propertyName == 'value'
                        && myresumeCommon.utils.throttle(callback, [input.val()], delay);
                } );
	        } 
	        else if( $.browser.ie && ( $.browser.version == 9 ) ) {
	            var timer;
	            var oldV = input.val();
	            input.bind( 'focus', function() {
                    timer = window.setInterval(function(){
                        var newV = input.val();
                        if(newV == oldV)
                            return;
                        // 值发生变化
                        oldV = newV;
                        // 回调函数
                        myresumeCommon.utils.throttle(callback, [oldV], delay);
                    }, 50);
                } );
	            input.bind( 'blur', function() {
                    window.clearInterval(timer);
                    timer = undefined;
                } );
	        }
	        else {
	            // 火狐、chrome完美支持
	            input.bind( 'input', function(e) {
                    myresumeCommon.utils.throttle(callback, [input.val()], delay);
                } );
	        }
	    }

	}

};
