// GLOBAL FUNCTIONS NEED TO MOVE INTO A NEW FILE
function ucwords(str)
{
    str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
    });

    return str;
}

jQuery.fn.selectText = function(){
    var doc = document;
    var element = this[0];
    console.log(this, element);
    if (doc.body.createTextRange) {
        var range = document.body.createTextRange();
        range.moveToElementText(element);
        range.select();
    } else if (window.getSelection) {
        var selection = window.getSelection();        
        var range = document.createRange();
        range.selectNodeContents(element);
        selection.removeAllRanges();
        selection.addRange(range);
    }
 };
 
function maxZIndex(win, dom) 
{
    if(!dom)
    {
        dom = document;
    }
    
    if(!win)
    {
        win = window;
    }
    var maxZ = Math.max.apply(null, win.$.map($('*', dom), function(e,n){
           if($(e, dom).css('position')=='absolute')
                return parseInt($(e).css('z-index'))||1 ;
           })
    );
    
    if(maxZ < 0)
    {
        maxZ = 0;
    }
    return maxZ;
}