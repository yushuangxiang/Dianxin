$(function(){
	
	/*****************************
	* 验证表单login弹窗
	*/

   	 	$("#loginForm").validate({
	        /*onkeyup: false,
	    	focusCleanup:true,*/
   	        rules: {
		    	   	email: {
		    	    	required: true,
		    	    	email: true,
		    	    	maxlength:100
		    	   	},
		    	   	password: {
		    	    	required: true
		    	   	}
		    	},
		    	messages: {
		    	   	email: {
		    	    	required: "请输入登录邮箱地址",
		    	    	email: "请输入有效的邮箱地址，如：vivi@lagou.com",
		    	    	maxlength:"请输入100字以内的邮箱地址"
		    	   	},
		    	   	password: {
		    	    	required: "请输入密码"
		    	   	}
		    	},
		    	submitHandler:function(form){
		    		if($('#remember').prop("checked")){
		      			$('#remember').val(1);
		      		}else{
		      			$('#remember').val(null);
		      		}
		    		var email = $('#email').val();
		    		var password = $('#password').val();
		    		var remember = $('#remember').val();
		    		$(form).find(":submit").attr("disabled", true);
		            $.ajax({
		            	type:'POST',
		            	data:{email:email,password:password,autoLogin:remember},
		            	url:ctx+'/user/login.json'
		            }).done(function(result) {
						if(result.success){
							var value = $(".collect_position").val() == "" ? null : $(".collect_position").val() ;
							if(value == "collected"){
								var collection = $('#jobCollection');
								collect(collection, result.resubmitToken);
							}
							top.location.reload();
						}else{
							$('#beError').text(result.msg).show();
						}
						$(form).find(":submit").attr("disabled", false);
		            }); 
		        }  
   		});
   	 	
   	 	
   	 /************************
   		 * colorbox错误弹窗信息提示
   		 */
   		 $("body").on("click","a.btn_s",function(){
   			$.colorbox.close();
   			parent.jQuery.colorbox.close();
   		});
   		$(".inline").colorbox({
   			inline:true
   		});
   		$(".errorTips").click(function(){
   			errorTips("上传附件格式错误!");
   		});
   		
   		/***************************
   		 * 注册激活页: 重新发送验证邮件
   		 */
   		$('#resend').click(function(){
   			$.ajax({
   	        	type:'POST',
   	        	url:ctx+'/user/resendActivatedMail'
   	        }).done(function(result) {
   	        	if(result.success){
   	        		$.colorbox({inline:true, href:"#resend_success",title:"验证邮件发送成功"});
   	        	}else{
   	        		alert(result.msg);
   	        	}
   	        });
   		});
   		
});