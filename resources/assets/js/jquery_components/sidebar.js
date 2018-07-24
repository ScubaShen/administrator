;(function(){

    'use strict';

    var sidebar = function(){

        $('.nav-title').on('click', function(){
            $(this).hasClass('text-fold') && $(this).removeClass('text-fold').addClass('text-unfold') ||
            $(this).hasClass('text-unfold') && $(this).removeClass('text-unfold').addClass('text-fold');
        });

        $('.sidebar-topbar').on('click', function(){
            let _this = $(this);
            if(_this.hasClass('sidebar-fold')){

                _this.removeClass('sidebar-fold').addClass('sidebar-unfold');
                _this.children().attr('class', 'glyphicon glyphicon-th-large');
                $('.panel-right').css('left','180px');
                $('#bpjgpt-sidebar').css('width','180px');

                document.cookie="sidebar=unfold;path=/";
            }
            else if(_this.hasClass('sidebar-unfold')){

                _this.removeClass('sidebar-unfold').addClass('sidebar-fold');
                _this.children().attr('class', 'glyphicon glyphicon-th');
                $('.panel-right').css('left','50px');
                $('#bpjgpt-sidebar').css('width','50px');

                document.cookie="sidebar=fold;path=/";
            }
        });
    };

    $(function(){
        sidebar();
    });
}());
