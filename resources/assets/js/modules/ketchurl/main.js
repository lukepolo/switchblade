// JAVASCRIPT ==============================================
// =========================================================


// BOOTSTRAP JS --------------------------------------------
// ---------------------------------------------------------

$('#').modal();
$('[data-toggle=popover]').popover({html:true});
$('[data-toggle=tooltip]').tooltip({html:true});

// BUTTON JS -----------------------------------------------
// ---------------------------------------------------------

// Lodaing button spinner
$('.enableLoading').on('click', function () {
	$(this).addClass('disabled loading');
});

// Toggle the I'm finished button styles
$(document).on('click', '#finishedBtn', function() { 
    $(this).parent().toggleClass('selected'); 
});

// Toggle the Settings menu
$(document).on('click', '.icon-cog', function() { 
   $(document.body).toggleClass('show-menu');
});

// FORM JS -------------------------------------------------
// ---------------------------------------------------------

// Prevent alpha characters on number input
$(document).ready(function() {
	$('.numeric').keydown(function(event) {
		// Allow only backspace and delete
		if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 38 ||  event.keyCode == 39 || event.keyCode == 40 ) {
			// let it happen, don't do anything
		}
		else {
			// Ensure that it is a number and stop the keypress
			if (event.keyCode < 48 || event.keyCode > 57 ) {
				event.preventDefault();	
			}	
		}
	});
});

// Autofill Log Form from Add Log button
$(document).on('click', '.btn-log, .btn-readAgain', function() {
	$('#bookTitle').val('The Art of Racing in the Rain').trigger('change');
	$('#logForm').addClass('focus');
});    

// Custom <select> box styling
$('select').each(function(){
    var $this = $(this), numberOfOptions = $(this).children('option').length;
  
    $this.addClass('select-hidden'); 
    $this.wrap('<div class="select"></div>');
    $this.after('<div class="select-styled"></div>');

    var $styledSelect = $this.next('div.select-styled');
    $styledSelect.text($this.children('option').eq(0).text());
  
    var $list = $('<ul />', {
        'class': 'select-options'
    }).insertAfter($styledSelect);
  
    for (var i = 0; i < numberOfOptions; i++) {
        $('<li />', {
            text: $this.children('option').eq(i).text(),
            rel: $this.children('option').eq(i).val()
        }).appendTo($list);
    }
  
    var $listItems = $list.children('li');
  
    $styledSelect.click(function(e) {
        e.stopPropagation();
        $('div.select-styled.active').each(function(){
            $(this).removeClass('active').next('ul.select-options').hide();
        });
        $(this).toggleClass('active').next('ul.select-options').toggle();
    });
  
    $listItems.click(function(e) {
        e.stopPropagation();
        $styledSelect.text($(this).text()).removeClass('active');
        $this.val($(this).attr('rel'));
        $list.hide();
        $(this).closest('.select').addClass('active filled');
    });
  
    $(document).click(function() {
        $styledSelect.removeClass('active');
        $list.hide();
    });

});


// FORM FLOAT LABELS ---------------------------------------
// ---------------------------------------------------------

// Float Label Show 
$(document).on('keypress change', 'input', function() {
	if($(this).parent().hasClass('k-picker-wrap')) {
		if($(this).val().length != 0) {
			$(this).closest('.k-datepicker').addClass('active');
        }
    }
    else {
        $(this).addClass('active');
    }
});

// Float Label Show for clicking the arrows on a numeric input
$(document).on('click', '.numeric', function() {
	if (this.value != '') {
		$(this).addClass('active');
	}
});

// Float Label Focus for Datepicker
$(document).on('focus', '.k-datepicker input', function() {
    $(this).closest('.k-datepicker').addClass('focus');
});

$(document).on('blur', '.k-datepicker input', function() {
    $(this).closest('.k-datepicker').removeClass('focus');
});		
		
		
// Float Label Hide
$(document).on('keyup', 'input', function() {
    if (this.value == '') {
        if($(this).parent().hasClass('k-picker-wrap')) {
            $(this).closest('.k-datepicker').removeClass('active');
        }
        else {
            $(this).removeClass('active');
        }
    }
});	