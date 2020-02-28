$(document).ready(function()
{
    /*
     *  Switch between from text and from file in upload code page.
     */

    // Initialize.
    enableCodeFromText();

    // Event listener: show from text and hide other.
    $('#switch_item_fromText').on('click', function()
    {
        enableCodeFromText();
    });

    // Event listener: show from file and hide other.
    $('#switch_item_fromFile').on('click', function()
    {
        enableCodeFromFile();
    });

    /*
     *  Add text button event for upload from text.
     */

    $('#uploadCode_button_addSourceText').on('click', function(){
        $('#uploadCode_list_sourceText').append(
            '\
                <li>\
                    <input style="margin:0px;" class="form_textbox" name="uploadCode_field_sourceText_filename[]" type="text" value="" placeholder="檔案名稱" />\
                    <textarea class="form_textarea" name="uploadCode_field_sourceText_code[]" placeholder="程式原始碼" required></textarea>\
                </li>\
            ');
    });

    /*
     *  Add file button event for upload from file.
     */

    $('#uploadCode_button_addSourceFile').on('click', function(){
        $('#uploadCode_list_sourceFile').append('<li><input name="uploadCode_field_sourceFile[]" type="file" /></li>');
    });

});

function enableCodeFromText()
{
    $('#switch_item_fromText').css('background-color', '#00000017');
    $('#switch_item_fromFile').css('background-color', '#ffffff');
    $('#uploadCode_sourceText').show();
    $('#uploadCode_sourceText input').prop('disabled', false);
    $('#uploadCode_sourceText textarea').prop('disabled', false);
    $('#uploadCode_sourceFile').hide();
    $('#uploadCode_sourceFile input').prop('disabled', true);
}

function enableCodeFromFile()
{
    $('#switch_item_fromText').css('background-color', '#ffffff');
    $('#switch_item_fromFile').css('background-color', '#00000017');
    $('#uploadCode_sourceText').hide();
    $('#uploadCode_sourceText input').prop('disabled', true);
    $('#uploadCode_sourceText textarea').prop('disabled', true);
    $('#uploadCode_sourceFile').show();
    $('#uploadCode_sourceFile input').prop('disabled', false);
}

