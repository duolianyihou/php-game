var Login = function() {
	var b = function() {
		if ($.fn.uniform) {
			$(":radio.uniform, :checkbox.uniform").uniform()
		}
	};
	var c = function() {
		$(".sign-up").click(function(h) {
			h.preventDefault();
			$(".login-form").slideUp(350,
			function() {
				$(".register-form").slideDown(350);
				$(".sign-up").hide()
			})
		});
		$(".back").click(function(h) {
			h.preventDefault();
			$(".register-form").slideUp(350,
			function() {
				$(".login-form").slideDown(350);
				$(".sign-up").show()
			})
		})
	};
	var g = function() {
		$(".forgot-password-link").click(function(h) {
			h.preventDefault();
			$(".forgot-password-form").slideToggle(200);
			$(".inner-box .close").fadeToggle(200)
		});
		$(".inner-box .close").click(function() {
			$(".forgot-password-link").click()
		})
	};
	var e = function() {
		if ($.validator) {
			$.extend($.validator.defaults, {
				errorClass: "has-error",
				validClass: "has-success",
				highlight: function(k, i, j) {
					if (k.type === "radio") {
						this.findByName(k.name).addClass(i).removeClass(j)
					} else {
						$(k).addClass(i).removeClass(j)
					}
					$(k).closest(".form-group").addClass(i).removeClass(j)
				},
				unhighlight: function(k, i, j) {
					if (k.type === "radio") {
						this.findByName(k.name).removeClass(i).addClass(j)
					} else {
						$(k).removeClass(i).addClass(j)
					}
					$(k).closest(".form-group").removeClass(i).addClass(j);
					$(k).closest(".form-group").find('label[generated="true"]').html("")
				}
			});
			var h = $.validator.prototype.resetForm;
			$.extend($.validator.prototype, {
				resetForm: function() {
					h.call(this);
					this.elements().closest(".form-group").removeClass(this.settings.errorClass + " " + this.settings.validClass)
				},
				showLabel: function(j, k) {
					var i = this.errorsFor(j);
					if (i.length) {
						i.removeClass(this.settings.validClass).addClass(this.settings.errorClass);
						if (i.attr("generated")) {
							i.html(k)
						}
					} else {
						i = $("<" + this.settings.errorElement + "/>").attr({
							"for": this.idOrName(j),
							generated: true
						}).addClass(this.settings.errorClass).addClass("help-block").html(k || "");
						if (this.settings.wrapper) {
							i = i.hide().show().wrap("<" + this.settings.wrapper + "/>").parent()
						}
						if (!this.labelContainer.append(i).length) {
							if (this.settings.errorPlacement) {
								this.settings.errorPlacement(i, $(j))
							} else {
								i.insertAfter(j)
							}
						}
					}
					if (!k && this.settings.success) {
						i.text("");
						if (typeof this.settings.success === "string") {
							i.addClass(this.settings.success)
						} else {
							this.settings.success(i, j)
						}
					}
					this.toShow = this.toShow.add(i)
				}
			})
		}
	};
	var d = function() {
		if ($.validator) {
			$(".login-form").validate({
				invalidHandler: function(i, h) {
					NProgress.start();
					$(".login-form .alert-danger").show();
					NProgress.done()
				},
				submitHandler: function(h) {
					NProgress.start();
					$.post($('#login_url').val(), { 
								username :  $("#l_username").val() , 
								password :  $("#l_password").val() ,
								act      :  $('.login-form .act').val() 
							}, function (data){
									NProgress.done();
									var d = $.parseJSON(data);
									if (d.status == 0){
											window.location.href = $('#loginSuccessUrl').val();
									} else {
										$(".login-form .alert-danger").html(d.msg);
										$(".login-form .alert-danger").show();
										$('#' + d.data.field).parent().parent().removeClass('has-success');
										$('#' + d.data.field).parent().parent().addClass('has-error');
									}									
							}
					);
				}
			})
		}
	};
	var f = function() {
		if ($.validator) {
			$(".forgot-password-form").validate({
				submitHandler: function(h) {
					$(".inner-box").slideUp(350,
					function() {
						$(".forgot-password-form").hide();
						$(".forgot-password-link").hide();
						$(".inner-box .close").hide();
						$(".forgot-password-done").show();
						$(".inner-box").slideDown(350)
					});
					return false
				}
			})
		}
	};
	var a = function() {
		if ($.validator) {
			$(".register-form").validate({
				invalidHandler: function(i, h) {
				},
				submitHandler: function(h) {
					NProgress.start();
					$.post($('#reg_url').val(), { 
								username :  $("#r_username").val() , 
								password :  $("#register_password").val() ,
                                                                password2 :  $("#passwrod2").val() ,
                                                                
							}, function (data){
									NProgress.done();
									var d = $.parseJSON(data);
									if (d.status == 0){
											window.location.href = $('#regSuccessUrl').val();
									} else {
										$(".register-form .alert-danger").html(d.msg);
										$(".register-form .alert-danger").show();
										$('#' + d.data.field).parent().parent().removeClass('has-success');
										$('#' + d.data.field).parent().parent().addClass('has-error');
									}									
							}
					);
				}
			})
		}
	};
	var setCn = function() {
		jQuery.extend(jQuery.validator.messages, {
		  required: "必选字段",
		  remote: "请修正该字段",
		  email: "请输入正确格式的电子邮件",
		  url: "请输入合法的网址",
		  date: "请输入合法的日期",
		  dateISO: "请输入合法的日期 (ISO).",
		  number: "请输入合法的数字",
		  digits: "只能输入整数",
		  creditcard: "请输入合法的信用卡号",
		  equalTo: "请再次输入相同的值",
		  accept: "请输入拥有合法后缀名的字符串",
		  maxlength: jQuery.validator.format("请输入一个 长度最多是 {0} 的字符串"),
		  minlength: jQuery.validator.format("请输入一个 长度最少是 {0} 的字符串"),
		  rangelength: jQuery.validator.format("请输入 一个长度介于 {0} 和 {1} 之间的字符串"),
		  range: jQuery.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),
		  max: jQuery.validator.format("请输入一个最大为{0} 的值"),
		  min: jQuery.validator.format("请输入一个最小为{0} 的值")
		});
	};

	return {
		init: function() {
			setCn();
			b();
			c();
			g();
			e();
			d();
			f();
			a();
		},
	}
} ();