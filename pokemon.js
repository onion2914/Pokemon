$(function(){
  'use strict';

  $('#pokemon_search').focus();

  // Search
  $('#pokemon_search_btn').on('click', function(){
    $("#pokemon_search_form").submit();
  });

  // Search
  $('#pokemon_search_form').on('submit', function(){
    // 検索ステータスを取得
    let status = {
        no : {'value' : $("#pokemon_search_no").val(), 'condition' : $("input[name='selector_no']:checked").val()},
        hp : {'value' : $("#pokemon_search_hp").val(), 'condition' : $("input[name='selector_hp']:checked").val()},
        attack : {'value' : $("#pokemon_search_attack").val(), 'condition' : $("input[name='selector_attack']:checked").val()},
        defence : {'value' : $("#pokemon_search_defence").val(), 'condition' : $("input[name='selector_defence']:checked").val()},
        spAttack : {'value' : $("#pokemon_search_spAttack").val(), 'condition' : $("input[name='selector_spAttack']:checked").val()},
        spDefence : {'value' : $("#pokemon_search_spDefence").val(), 'condition' : $("input[name='selector_spDefence']:checked").val()},
        speed : {'value' : $("#pokemon_search_speed").val(), 'condition' : $("input[name='selector_speed']:checked").val()},
    };
    
    // console.log(status);
    $.post('_ajax.php', {
      status: status,
      mode: 'search',
      token: $('#token').val(),
    }, function(res){
      console.log(res);
      $('#searched_info tbody tr').remove();
      Object.keys(res).forEach(key => {

        //検索結果を表示
        let info_r = $('<tr></tr>').appendTo('#searched_info > tbody');
        
        // console.log(res[key]);
        $(
          `<th><figure class="img__loadItem"><img src="http://study0study.php.xdomain.jp/thumbnails/${('000' + res[key].pokemonNo).slice( -3 )}.png" alt="画像ないよ" class="img"></figure></th>`
        ).appendTo(info_r);

        $('<th>' + res[key].pokemonNo + '</th>').appendTo(info_r);
        $('<th>' + res[key].name + '</th>').appendTo(info_r);
        $('<th>' + res[key].types + '</th>').appendTo(info_r);
        $('<th>' + res[key].hp + '</th>').appendTo(info_r);
        $('<th>' + res[key].attack + '</th>').appendTo(info_r);
        $('<th>' + res[key].defence + '</th>').appendTo(info_r);
        $('<th>' + res[key].spAttack + '</th>').appendTo(info_r);
        $('<th>' + res[key].spDefence + '</th>').appendTo(info_r);
        $('<th>' + res[key].speed + '</th>').appendTo(info_r);
      });
    });

    return false;

  });

  // Sort
  //idがsorting_btnで始まる要素を取得し、それぞれにイベントを登録する



  class SortingColumn {
    constructor(colNum, text, hidden) {
      this.state = 0;

      const th = $('<th>').appendTo('#searched_info thead tr');
      
      this.col = colNum;
      this.text = th.text(text);

      this.btn = $('<span>', {
        id : 'sorting_btn_' + colNum,
        class: 'sorting_btn sorting_btn_none',
        text: '▼'
      }).appendTo(th);

      if(hidden){
        this.btn.attr('style', 'visibility:hidden;')
      }
    }

    resetState() {
      this.state = 0;
      this.btn.text('▼');
      this.btn.removeClass('sorting_btn_ascending');
      this.btn.removeClass('sorting_btn_descending');
      this.btn.addClass('sorting_btn_none');
    }

    switchState(){
      switch(this.state){
        case 0:
          this.state=1;
          this.btn.text('▼');
          this.btn.addClass('sorting_btn_descending');
          this.btn.removeClass('sorting_btn_none');
          this.sortData(false);
          break;
        case 1:
          this.state=2;
          this.btn.text('▲');
          this.btn.addClass('sorting_btn_ascending');
          this.btn.removeClass('sorting_btn_descending');
          //$('#sorting_btn_' + this.col).attr('text' ,'▲');
          // this.btn.attr('text' ,'▲');
          this.sortData(true);
          break;
        case 2:
          this.state=1;
          this.btn.text('▼');
          this.btn.removeClass('sorting_btn_ascending');
          this.btn.addClass('sorting_btn_descending');
          this.sortData(false);
          break;
        default:
          alert('Error!');
      }
    }

    sortData(ascending){
      $('#searched_info tbody tr').sort((a, b) => {
        let _aText = $(a).find('th').eq(this.col).text();
        let _bText = $(b).find('th').eq(this.col).text();
        let _a = isNaN(Number(_aText)) ? _aText : Number(_aText);
        let _b = isNaN(Number(_bText)) ? _bText : Number(_bText);

        if(ascending){    //昇順
          if(_a < _b) return -1;
          if(_a > _b) return 1;
        }else{            //降順
          if(_a > _b) return -1;
          if(_a < _b) return 1;
        }
        return 0;
      }).appendTo('#searched_info tbody');
    }
  }

  const sortingColumns = [
    new SortingColumn(0,'',true),
    new SortingColumn(1, 'No',false),
    new SortingColumn(2, '名前',false),
    new SortingColumn(3, 'タイプ',false),
    new SortingColumn(4, 'HP',false),
    new SortingColumn(5, '攻撃',false),
    new SortingColumn(6, '防御',false),
    new SortingColumn(7, '特殊攻撃',false),
    new SortingColumn(8, '特殊防御',false),
    new SortingColumn(9, 'すばやさ',false),
  ];

  $('#searched_info thead').on('click', 'span', () => {
    const col_no = $('#searched_info thead th').index($(event.target).parent());
    sortingColumns.forEach((column) => {
      if(column.col == col_no){
        column.switchState();
      }else{
        column.resetState();
      }
    });
  });

  // $('[id^="sorting_btn"]').on('click',() => {
  //   const col_no = $('#searched_info thead th').index($(event.currentTarget).parent());
  //   $('#searched_info tbody tr').sort((a, b) => {
  //     if($(a).find('th').eq(col_no).text() > $(b).find('th').eq(col_no).text()) return -1;
  //     if($(a).find('th').eq(col_no).text() < $(b).find('th').eq(col_no).text()) return 1;
  //     return 0;
  //   }).appendTo('#searched_info tbody');
  // });




    // $('#sorting_btn_no').on('click', () => {
    //   // console.log($('#searched_info tbody tr'));
    //   //押されたボタンの列要素を取得
    //   const col = $("#sorting_btn_no").parent();
    //   const col_no = $('#searched_info thead th').index(col);
    //   // console.log(col_no);
    //   $('#searched_info tbody tr').sort((a, b) => {
    //     if($(a).find('th').eq(col_no).text() > $(b).find('th').eq(col_no).text()) return -1;
    //     if($(a).find('th').eq(col_no).text() < $(b).find('th').eq(col_no).text()) return 1;
    //     return 0;
    //   }).appendTo('#searched_info tbody');
    // });
});