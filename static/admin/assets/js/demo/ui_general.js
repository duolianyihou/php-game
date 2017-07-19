"use strict";
$(document).ready(function(){
    /**
     * 插件网站示例
     * http://www.css88.com/jquery-ui-api/datepicker/index.html
     */
    $(".datepicker").datepicker({
//        numberOfMonths: [2,3],
        numberOfMonths: 3,
        defaultDate:+7,
//        showButtonPanel: true,        
        showOtherMonths:true,
        autoSize:true,
        dateFormat:"yy-mm-dd",
        dayNamesMin: ['日', '一', '二', '三', '四', '五', '六'],
        monthNames:['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],
        prevText:'前一月',
        nextText:'后一月',        
//        currentText:'今天',
        closeText:'关闭',
        showCurrentAtPos: 1,
        yearSuffix: "年",
//        yearRange: "2002:2012"
//        stepMonths: 1,
//        showWeek: true,
//        showMonthAfterYear: true,
        duration: "slideDown"
        
    });
    $(".inlinepicker").datepicker({
        inline:true,
        showOtherMonths:true
    });
    /**
     * 插件网站示例
     * http://amsul.ca/pickadate.js/date/#translations
     */
    $(".datepicker-fullscreen").pickadate({
        today: '今天',
        clear: '清除',
        format: 'yyyy-mm-dd',
        monthsShort: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
        weekdaysShort: ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'],
        showMonthsShort: true
    });
    /**
     * 插件网站示例
     * http://amsul.ca/pickadate.js/time/#formats
     */
    $(".timepicker-fullscreen").pickatime({
        format: 'H:i A'
    });
    
    
    var a=$("body")[0].style;
    $("#colorpicker-event").colorpicker().on("changeColor",function(b){
        a.backgroundColor=b.color.toHex()
        });
    $(".btn-notification").click(function(){
        var b=$(this);
        noty({
            text:b.data("text"),
            type:b.data("type"),
            layout:b.data("layout"),
            timeout:2000,
            modal:b.data("modal"),
            buttons:(b.data("type")!="confirm")?false:[{
                addClass:"btn btn-primary",
                text:"Ok",
                onClick:function(c){
                    c.close();
                    noty({
                        force:true,
                        text:'You clicked "Ok" button',
                        type:"success",
                        layout:b.data("layout")
                        })
                    }
                },{
            addClass:"btn btn-danger",
            text:"Cancel",
            onClick:function(c){
                c.close();
                noty({
                    force:true,
                    text:'You clicked "Cancel" button',
                    type:"error",
                    layout:b.data("layout")
                    })
                }
            }]
        });
    return false
    });
$(".btn-nprogress-start").click(function(){
    NProgress.start();
    $("#nprogress-info-msg").slideDown(200)
    });
$(".btn-nprogress-set-40").click(function(){
    NProgress.set(0.4)
    });
$(".btn-nprogress-inc").click(function(){
    NProgress.inc()
    });
$(".btn-nprogress-done").click(function(){
    NProgress.done();
    $("#nprogress-info-msg").slideUp(200)
    });
$("a.basic-alert").click(function(b){
    b.preventDefault();
    bootbox.alert("Hello world!",function(){
        console.log("Alert Callback")
        })
    });
$("a.confirm-dialog").click(function(b){
    b.preventDefault();
    var url = this.href;
    bootbox.confirm({
            message: "确定要删除吗?",
            buttons: {
              confirm: {
                 label: "确认",
                 className: "btn-primary btn-sm"
              },
              cancel: {
                 label: "取消",
                 className: "btn-sm"
              }
            },
            callback: function(result) {
                if(result) {
                     window.location.href = url;
                }
            }
        })
    });
$("a.multiple-buttons").click(function(b){
    b.preventDefault();
    bootbox.dialog({
        message:"I am a custom dialog",
        title:"Custom title",
        buttons:{
            success:{
                label:"Success!",
                className:"btn-success",
                callback:function(){
                    console.log("great success")
                    }
                },
        danger:{
            label:"Danger!",
            className:"btn-danger",
            callback:function(){
                console.log("uh oh, look out!")
                }
            },
    main:{
        label:"Click ME!",
        className:"btn-primary",
        callback:function(){
            console.log("Primary button")
            }
        }
    }
})
});
$("a.multiple-dialogs").click(function(b){
    b.preventDefault();
    bootbox.alert("Prepare for multiboxes in 1 second...");
    setTimeout(function(){
        bootbox.dialog({
            message:"Do you like Melon?",
            title:"Fancy Title",
            buttons:{
                danger:{
                    label:"No :-(",
                    className:"btn-danger",
                    callback:function(){
                        bootbox.alert("Aww boo. Click the button below to get rid of all these popups.",function(){
                            bootbox.hideAll()
                            })
                        }
                    },
            success:{
                label:"Oh yeah!",
                className:"btn-success",
                callback:function(){
                    bootbox.alert("Glad to hear it! Click the button below to get rid of all these popups.",function(){
                        bootbox.hideAll()
                        })
                    }
                }
        }
    })
},1000)
});
$("a.programmatic-close").click(function(c){
    c.preventDefault();
    var b=bootbox.alert("This dialog will automatically close in two seconds...");
    setTimeout(function(){
        b.modal("hide")
        },2000)
    })
});