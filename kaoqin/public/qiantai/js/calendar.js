/**
 * 完整代码
 */


// 关于月份： 在设置时要-1，使用时要+1
$(function () {
  $('#calendar').calendar({
    ifSwitch: true, // 是否切换月份
    hoverDate: true, // hover是否显示当天信息
    backToday: true // 是否返回当天
  });

});

;(function ($, window, document, undefined) {
  var Calendar = function (elem, options) {
    this.$calendar = elem;
    this.defaults = {
      ifSwitch: true,
      hoverDate: false,
      backToday: false,
      startWeek:1
    };

    this.opts = $.extend({}, this.defaults, options);

    // console.log(this.opts);
  };
  var clickdate, calenData = [],nowday,presentmonth;
  Calendar.prototype = {
    showHoverInfo: function (obj) { // hover 时显示当天信息
      var _dateStr = $(obj).attr('data');
      var offset_t = $(obj).offset().top + (this.$calendar_today.height() - $(obj).height()) / 2;
      var offset_l = $(obj).offset().left + $(obj).width();
      var changeStr = addMark(_dateStr);
      var _week = changingStr(changeStr).getDay();
      nowday=_week;
      var _weekStr = '';
      // this.$calendar_today.show();
      this.$calendar_today
        .css({
          left: offset_l + 30,
          top: offset_t
        })
        .stop()
        .animate({
          left: offset_l + 16,
          top: offset_t
        });
      switch (_week) {
        case 1:
          _weekStr = '一';
          break;
        case 2:
          _weekStr = '二';
          break;
        case 3:
          _weekStr = '三';
          break;
        case 4:
          _weekStr = '四';
          break;
        case 5:
          _weekStr = '五';
          break;
        case 6:
          _weekStr = '六';
          break;
        case 0:
          _weekStr = '日';
          break;
      }
      this.$calendarToday_date.text(changeStr);
      this.$calendarToday_week.text(_weekStr);
    },
    showCalendar: function () { // 输入数据并显示
      var self = this;
      var year = dateObj.getDate().getFullYear();
      var month = dateObj.getDate().getMonth() + 1;
      var dateStr = returnDateStr(dateObj.getDate());
      var firstDay = new Date(year, month - 1, 1); // 当前月的第一天
      this.$calendarTitle_text.text(year + '-' + dateStr.substr(4, 2));
      var nowweek;
      switch (new Date().getDay()) {
          case 1:
              nowweek = '一';
              break;
          case 2:
              nowweek = '二';
              break;
          case 3:
              nowweek = '三';
              break;
          case 4:
              nowweek = '四';
              break;
          case 5:
              nowweek = '五';
              break;
          case 6:
              nowweek = '六';
              break;
          case 0:
              nowweek = '日';
              break;
      }
      this.$calendarDate_item.each(function (i) {
        // allDay: 得到当前列表显示的所有天数
        var allDay = new Date(year, month - 1, i +1-firstDay.getDay());
        var allDay_str = returnDateStr(allDay);
        $(this).text(allDay.getDate()).attr('data', allDay_str);
          self.showHoverInfo($(this));
        if (returnDateStr(new Date()) === allDay_str) {
          $(this).attr('class', 'item item-curDay');
          $(this).parent().attr('style', 'display:block');
        }
        else if (returnDateStr(firstDay).substr(0, 6) === allDay_str.substr(0, 6)) {
            if(nowday==6 || nowday==0){
                $(this).attr('class', 'item weekend');
                $(this).parent().attr('style', 'display:block');
            }else{
                $(this).attr('class', 'item item-curMonth');
                $(this).parent().attr('style', 'display:block');
            }
        } else {
            $(this).attr('class', 'item');
            if(i>=7){
                $(this).parent().attr('style', 'display:none');
            }
        }
      });
      $('.checkdate').html(returnDateStr(new Date()).substr(0, 4) + '-' + returnDateStr(new Date()).substr(4, 2) + '-' + returnDateStr(new Date()).substring(6));
      $('.week').html(nowweek);
      // 已选择的情况下，切换日期也不会改变
      if (self.selected_data) {
        var selected_elem = self.$calendar_date.find('[data=' + self.selected_data + ']');
        selected_elem.addClass('item-selected');
      }
    },
    renderDOM: function () { // 渲染DOM
      this.$calendar_title = $('<div class="calendar-title" style="display: none"></div>');
      this.$calendar_week = $('<ul class="calendar-week"></ul>');
      this.$calendar_date = $('<ul class="calendar-date"></ul>');
      this.$calendar_today = $('<div class="calendar-today"></div>');


      var _titleStr =
        '<div class="arrow">' +
        '<span class="arrow-prev"><</span>' +
        '<a href="#" class="title"></a>' +
        '<span class="arrow-next">></span>' +
        '</div>';
      var _weekStr = '<li class="item">日</li>' +
        '<li class="item">一</li>' +
        '<li class="item">二</li>' +
        '<li class="item">三</li>' +
        '<li class="item">四</li>' +
        '<li class="item">五</li>' +
        '<li class="item">六</li>';
      var _dateStr = '';
      var _dayStr = '<i class="triangle"></i>' +
        '<p class="date"></p>' +
        '<p class="week"></p>';
      for (var i = 0; i < 6; i++) {
        _dateStr += '<div><li class="item">26</li><span></span></div>' +
          '<div><li class="item">26</li><span></span></div>' +
          '<div><li class="item">26</li><span></span></div>' +
          '<div><li class="item">26</li><span></span></div>' +
          '<div><li class="item">26</li><span></span></div>' +
          '<div><li class="item">26</li><span></span></div>' +
          '<div><li class="item">26</li><span></span></div>';
      }
      this.$calendar_title.html(_titleStr);
      this.$calendar_week.html(_weekStr);
      this.$calendar_date.html(_dateStr);
      this.$calendar_today.html(_dayStr);
        this.$calendar.html("");
      this.$calendar.append(this.$calendar_title, this.$calendar_week, this.$calendar_date, this.$calendar_today);
      this.$calendar.show();
    },

    inital: function () { // 初始化
      var self = this;

      this.renderDOM();

      this.$calendarTitle_text = this.$calendar_title.find('.title');
      this.$backToday = $('#backToday');
      this.$arrow_prev = this.$calendar_title.find('.arrow-prev');
      this.$arrow_next = this.$calendar_title.find('.arrow-next');
      this.$calendarDate_item = this.$calendar_date.find('.item');
      this.$calendarToday_date = this.$calendar_today.find('.date');
      this.$calendarToday_week = this.$calendar_today.find('.week');
      this.selected_data = 0;
      this.showCalendar();
      if (this.opts.ifSwitch) {
        this.$arrow_prev.bind('click', function () {
          var _date = dateObj.getDate();
          dateObj.setDate(new Date(_date.getFullYear(), _date.getMonth() - 1, 1));
          allthis.$calendarDate_item.each(function (i) {
              $(this).next().html('');
          });
          rendertime();
          self.showCalendar();
        });

        this.$arrow_next.bind('click', function () {
          var _date = dateObj.getDate();
          dateObj.setDate(new Date(_date.getFullYear(), _date.getMonth() + 1, 1));
          allthis.$calendarDate_item.each(function (i) {
              $(this).next().html('');
          });
          rendertime();
          self.showCalendar();
        });
      }
      if (this.opts.backToday) {
        var cur_month = dateObj.getDate().getMonth() + 1;
        this.$backToday.bind('click', function () {
          var item_month = $('.item-curMonth').eq(0).attr('data').substr(4, 2);
          var if_lastDay = (item_month != cur_month) ? true : false;

          if (!self.$calendarDate_item.hasClass('item-curDay') || if_lastDay) {
            dateObj.setDate(new Date());

            self.showCalendar();
          }
        });
      }
      this.$calendarDate_item.hover(function () {
        self.showHoverInfo($(this));
      }, function () {
        self.$calendar_today.css({
          left: 0,
          top: 0
        }).hide();
      });
      //弹窗
      var tips_flag = false;

      function closeTips(tips, delay, callback) {
        if (tips_flag == false) {
          tips_flag = true;
          setTimeout(function () {
            layui.layer.tips(tips, '.layui-layer-setwin .layui-layer-close', {
              tips: 3
            });

            if (callback) {
              callback.call(this);
            }

          }, (delay ? delay : 300));
        }
      }
      //点击某天
      this.$calendarDate_item.click(function () {
        var onethis = $(this);
        var _dateStr = $(this).attr('data');
        console.log(_dateStr)
        var _date = changingStr(addMark(_dateStr));
        returnDateStr(_date);
        //循环日程列表
        self.$calendarDate_item.each(function (i) {
          var id = onethis.next().attr("id");
          var title = onethis.next().attr("title");
          if (title) {
            layui.layer.open({
              title: title,
              type: 2,
              shadeClose: true,
              shade: [0.6, '#393D49'],
              area: ['600px', '380px'],
              content: "../../page/user/plan_add.html?id=" + id,
              success: function (layero, index) {
                closeTips('点击此处返回');
              },
              end:function () {
                  rendertime();
              }
            });
          } else {

          }
          return false;
        });
        var $curClick = null;
        self.selected_data = $(this).attr('data');
        dateObj.setDate(new Date(_date.getFullYear(), _date.getMonth(), 1));
        if (!$(this).hasClass('item-curMonth')) {
          self.showCalendar();
        }
        $curClick = self.$calendar_date.find('[data=' + _dateStr + ']');
        $curDay = self.$calendar_date.find('.item-curDay');
        if (!$curClick.hasClass('item-selected')) {
          self.$calendarDate_item.removeClass('item-selected');
          $curClick.addClass('item-selected');
        }
        $('.checkdate').html(returnDateStr(_date).substr(0, 4) + '-' + returnDateStr(_date).substr(4, 2) + '-' + returnDateStr(_date).substring(6));
      });
      //获取日历列表
      var calenData = {};
      //添加日程页面渲染
      var allthis = this;
      rendertime();

      function rendertime() {
        // sendAjax('/index/plac_sel?sign=' + localStorage.getItem("RAY-SIGN"), 'get', '', function (data) {
        //   calenData = data.data;
        //   if (calenData.length > 0) {
        //     for (var m = 0; m < calenData.length; m++) {
        //       allthis.$calendarDate_item.each(function (i) {
        //         var datas = $(this).attr("data").substr(0, 4) + '-' + $(this).attr("data").substr(4, 2) + '-' + $(this).attr("data").substring(6);
        //         if (calenData[m].start.substr(0, 10) == datas) {
        //             $(this).next().html(calenData[m].content).attr("id", calenData[m].id).attr("title", calenData[m].title);
        //         }
        //       })
        //     }
        //   }
        // })
      }

        $('.calenbox').on('click','.clickmonth',function () {
          var index=$(this).index();
          $('.clickmonth img').attr("src","images/checkno.png").eq(index).attr("src","images/check.png");
          $('.box,.calenbox').fadeOut();
          var clickdate=$(this).data("date").split("-");
          var _date = dateObj.getDate();
          dateObj.setDate(new Date(clickdate[0], clickdate[1]-1, 1));
          self.$calendarDate_item.each(function (i) {
              $(this).next().html('');
          });
          rendertime();
          self.showCalendar();
          $('.checkdate').html(clickdate[0]+'-'+clickdate[1]+'-'+'01');
          var checkdate;
          switch (new Date(clickdate[0]+'-'+clickdate[1]+'-'+'01').getDay()) {
              case 1:
                  checkdate = '一';
                  break;
              case 2:
                  checkdate = '二';
                  break;
              case 3:
                  checkdate = '三';
                  break;
              case 4:
                  checkdate = '四';
                  break;
              case 5:
                  checkdate = '五';
                  break;
              case 6:
                  checkdate = '六';
                  break;
              case 0:
                  checkdate = '日';
                  break;
          }
          $('.week').html(checkdate);
          self.$calendarDate_item.each(function (i) {
              var blockdate=$(this).attr("data").substr(0, 4) + '-' + $(this).attr("data").substr(4, 2) + '-' + $(this).attr("data").substring(6);
              if(clickdate[1]!=addMark(returnDateStr(new Date())).split("-")[1]) {
                  if ((clickdate[0]+'-'+clickdate[1]+'-'+'01') === blockdate) {
                      $(this).attr('class', 'item item-curDay');
                      $(this).parent().attr('style', 'display:block');
                  }
              }
          })
        });
    },
    constructor: Calendar
  };

  $.fn.calendar = function (options) {
    var calendar = new Calendar(this, options);

    return calendar.inital();
  };


  // ========== 使用到的方法 ==========

  var dateObj = (function () {
    var _date = new Date();

    return {
      getDate: function () {
        return _date;
      },

      setDate: function (date) {
        _date = date;
      }
    }
  })();

  function returnDateStr(date) { // 日期转字符串
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    var week=date.getDay(),_weekStr;
    switch (week) {
        case 1:
            _weekStr = '一';
            break;
        case 2:
            _weekStr = '二';
            break;
        case 3:
            _weekStr = '三';
            break;
        case 4:
            _weekStr = '四';
            break;
        case 5:
            _weekStr = '五';
            break;
        case 6:
            _weekStr = '六';
            break;
        case 0:
            _weekStr = '日';
            break;
    }
    $('.week').html(_weekStr);
    month = month <= 9 ? ('0' + month) : ('' + month);
    day = day <= 9 ? ('0' + day) : ('' + day);
    var clickdate = year + '-' + month + '-' + day;
    return year + month + day;
    return clickdate;
  }
  function changingStr(fDate) { // 字符串转日期
    var fullDate = fDate.split("-");

    return new Date(fullDate[0], fullDate[1] - 1, fullDate[2]);
  };

  function addMark(dateStr) { // 给传进来的日期字符串加-
    return dateStr.substr(0, 4) + '-' + dateStr.substr(4, 2) + '-' + dateStr.substring(6);
  }

  // 条件1：年份必须要能被4整除
  // 条件2：年份不能是整百数
  // 条件3：年份是400的倍数
  function isLeapYear(year) { // 判断闰年
    return (year % 4 == 0) && (year % 100 != 0 || year % 400 == 0);
  }

    var myDate = new Date();
    var day;    //日
    var month;  //月
    var year;   //年
    var dateStr = "";
    var dateone="";
    for(var i=0; i<10; i++) {
        //获取当天
        day = (myDate.getDate()<10?"0":"")+myDate.getDate();
        var nowmonth=myDate.getMonth()+1;
        //获取上一个月份
        if((myDate.getMonth()+1-i) < 1) {
            month = ((12+(myDate.getMonth()+1-i))<10?"0":"")+(12+(myDate.getMonth()+1-i));
            year = myDate.getFullYear()-1;
        } else {
            month = ((myDate.getMonth()+1-i)<10?"0":"")+(myDate.getMonth()+1-i);
            year = myDate.getFullYear();
        }
        if(i > 0) {
            dateStr += ",";
        }
        if(month==nowmonth){
            dateone+='<div class="clickmonth" data-date="'+year+'-'+month+'-'+day+'">' +
                '<img src="images/check.png">' +
                year+"年"+month+"月" +
                '</div>'
        }else{
            dateone+='<div class="clickmonth" data-date="'+year+'-'+month+'-'+day+'">' +
                '<img src="images/checkno.png">' +
                year+"年"+month+"月" +
                '</div>'
        }
        dateStr += year+"年"+month+"月";
    }
    $('.calenbox').html(dateone);
})(jQuery, window, document);