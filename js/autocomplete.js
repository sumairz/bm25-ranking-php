$(function(){


/* Sumair modification to load final index keys for auto-complete */

// Your file path
var filePath = "final_index.json";
availableTags = [];

// Ajax request to read json file and load keys
$.ajax({
    type: "POST",
    url: filePath,
    //data: "{'FileName':'" + fileName + "'}",
    contentType: "application/json; charset=utf-8",
    dataType: "json",
    success: function (response) {
		$.each(response, function(key, value){
		    availableTags.push(key);
			
		});
       }	   
});

/* END modification */


/*var availableTags = [
    "ActionScript",
    "AppleScript",
    "Asp",
    "BASIC",
    "C",
    "C++",
    "Clojure",
    "COBOL",
    "ColdFusion",
    "Erlang",
    "Fortran",
    "Groovy",
    "Haskell",
    "Java",
    "JavaScript",
    "Lisp",
    "Perl",
    "PHP",
    "Python",
    "Ruby",
    "Scala",
    "Scheme"
];
*/
var faux = $("#faux");
var arrayused;
var calcfaux;
var retresult;
var checkspace;
var contents = $('#tags')[0];
var carpos;
var fauxpos;
var tier;
var startss;
var endss;
function getCaret(el) {
  if (el.selectionStart) {
    return el.selectionStart;
  } else if (document.selection) {
    el.focus();

    var r = document.selection.createRange();
    if (r == null) {
      return 0;
    }

    var re = el.createTextRange(),
        rc = re.duplicate();
    re.moveToBookmark(r.getBookmark());
    rc.setEndPoint('EndToStart', re);

    return rc.text.length;
  } 
  return 0;
}

                function split( val ) {
                return val.split( / \s*/ );
                }
                function extractLast( term ) {
                return split( term ).pop();
                }
                $( "#tags" )
                .on( "keydown", function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB &&
                $( this ).data( "autocomplete" ).menu.active ) {
                event.preventDefault();
                }
                })
                .click( function( event ) {
                carpos = getCaret(contents);
                fauxpos = faux.text().length;
                if(carpos < fauxpos) {
                tier = "close";
                $(this).autocomplete( "close" );
                startss = this.selectionStart;
                endss = this.selectionEnd;
                $(this).val( $(this).val().replace(/ *$/,''));
                this.setSelectionRange(startss, endss);
                }
                else
                {
                tier = "open";
                }                 
               
                })
                .on( "keyup", function( event ) {
                calcfaux = faux.text($(this).val());
                fauxpos = faux.text().length;    
                if(/ $/.test(faux.text()) || tier === "close") {
                checkspace = "space";
                }
                else
                {
                checkspace = "nospace";
                } 
                

                if (fauxpos <= 1)
                {
                tier = "open";
                }
                carpos = getCaret(contents);
                if(carpos < fauxpos) {
                tier = "close";
                $(this).autocomplete( "close" );
                startss = this.selectionStart;
                endss = this.selectionEnd;
                $(this).val( $(this).val().replace(/ *$/,''));
                this.setSelectionRange(startss, endss);
                }
                else
                {
                tier = "open";
                }
                })
                //mouse caret position dropdown
                .autocomplete({
                minLength: 1,  
                search: function( event, ui ) { 
                var input = $( event.target );
                // custom minLength
                if (checkspace === "space") {  
                input.autocomplete( "close" );
                return false;
                }
                },
                source: function (request, response) {
                  
                var term = $.ui.autocomplete.escapeRegex(extractLast(request.term))
                // Create two regular expressions, one to find suggestions starting with the user's input:
                , startsWithMatcher = new RegExp("^" + term, "i")
                , startsWith = $.grep(availableTags, function(value) {
                    return startsWithMatcher.test(value.label || value.value || value);
                })
                // ... And another to find suggestions that just contain the user's input:
                , containsMatcher = new RegExp(term, "i")
                , contains = $.grep(availableTags, function (value) {
                    return $.inArray(value, startsWith) < 0 &&
                        containsMatcher.test(value.label || value.value || value);
                });

                // Supply the widget with an array containing the suggestions that start with the user's input,
                // followed by those that just contain the user's input.
                response(startsWith.concat(contains));                
                },
                open: function( event, ui ) {
                    var input = $( event.target ),
                        widget = input.autocomplete( "widget" ),
                        style = $.extend( input.css( [
                            "font",
                            "border-left",
                            "padding-left"
                        ] ), {
                            position: "absolute",
                            visibility: "hidden",
                            "padding-right": 0,
                            "border-right": 0,
                            "white-space": "pre"
                        } ),
                        div = $( "<div/>" ),
                        pos = {    
                            my: "left top",
                            collision: "none"        
                        },
                offset = -7; // magic number to align the first letter
                             // in the text field with the first letter
                             // of suggestions
                             // depends on how you style the autocomplete box

                    widget.css( "width", "" );

                    div
                        .text( input.val().replace( /\S*$/, "" ) )
                        .css( style )
                        .insertAfter( input );
                    offset = Math.min(
                        Math.max( offset + div.width(), 0 ),
                        input.width() - widget.width()
                    );
                    div.remove();

                    pos.at = "left+" + offset + " bottom";
                    input.autocomplete( "option", "position", pos );        

                    widget.position( $.extend( { of: input }, pos ) );
                },        
                focus: function() {
                // prevent value inserted on focus
                return false;
                },
                select: function( event, ui ) {
                var terms = split( this.value );
                startss = this.selectionStart;
                endss = this.selectionEnd;
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push( ui.item.value );
                // add placeholder to get the comma-and-space at the end
                terms.push( "" );
                this.setSelectionRange(startss, endss);
                this.value = terms.join( " " );
                calcfaux = faux.text($(this).val());
                if(/ $/.test(faux.text())) {
                checkspace = "space";
                }
                else
                {
                checkspace = "nospace";
                }
                carpos = getCaret(contents);
                fauxpos = faux.text().length;
                return false;
                }
                });
});