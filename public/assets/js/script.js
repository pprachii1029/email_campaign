jQuery('.user_selection .dropdown-item').on('click',function(){
  jQuery('.user_selection .dropdown-item').removeClass('active');
  jQuery(this).addClass('active');
  jQuery('.selected_groupView').html(jQuery(this).html());
});