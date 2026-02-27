//let base_url = "http://www.mirrel.com/";
//let tinyMCEconnector = 'http://localhost/website/mirrel5/admin/elFinder-2.1.50/php/connector.minimal.php';
var error = 0;
window.onerror = function (e) {
    console.log(e);
}

if (window.self === window.top) {
    var divsToHide;
    divsToHide = document.getElementsByClassName("btn-mirrel");
    for (var i = 0; i < divsToHide.length; i++) {
        divsToHide[i].style.visibility = "hidden";
        divsToHide[i].style.display = "none";
    }

    divsToHide = document.getElementsByClassName("fnDelete");
    for (var i = 0; i < divsToHide.length; i++) {
        divsToHide[i].style.visibility = "hidden";
        divsToHide[i].style.display = "none";
    }

    divsToHide = document.getElementsByClassName("fnModal");
    for (var i = 0; i < divsToHide.length; i++) {
        divsToHide[i].style.visibility = "hidden";
        divsToHide[i].style.display = "none";
    }

    divsToHide = document.getElementsByClassName("handle");
    for (var i = 0; i < divsToHide.length; i++) {
        divsToHide[i].style.visibility = "hidden";
        divsToHide[i].style.display = "none";
    }
}



// make instance
const mceElf = new tinymceElfinder({
    // connector URL (Use elFinder Demo site's connector for this demo)
    url: tinyMCEconnector,
    // upload target folder hash for this tinyMCE
    uploadTargetHash: 'l3_TUNFX0ltZ3M', // l3 MCE_Imgs on elFinder Demo site for this demo
    // elFinder dialog node id
    nodeId: 'elfinder'
});

if (typeof jQuery != 'undefined') {

    sendData = {
        function: "fnUpdateUrl",
        data: current_url
    }
    console.log('sendData', sendData);
    parent.postMessage(sendData, "*");


    $(document).ready(function () {
        //PageLoad(); 
        if (window.self !== window.top) {
            onchanges();
            fnModal();
            sortable();
            fnAddContent();
            fnDelete();
            fnInsert();
            fnRouter();

            richEditor();

        }

    });
} else {
    alert('Please update your jquery 3.x');
}


function emit(evt) {

    //  message = "I got " + evt.data + " from " + evt.origin;
    if (evt.data['function'] == 'redirect') {
        redirect(evt.data['data']);
    }
    if (evt.data['function'] == 'refresh') {
        location.reload();
    }
}
window.addEventListener("message", emit, false)


function redirect(data) {
    window.location.href = data;
}

function PageLoad() {
    console.log('jQuery', jQuery.fn.jquery);
    console.log('base_url', base_url);
}

function onchanges() {
    
    $(".update").on("change", function () {

        if (!this.id) {
            alert('Please add unique ID, example id="hihihi-123" ')
        } else {
            
            post = {
                data: $('#' + this.id).data(),  
                value: $('#' + this.id).val()
            }
            console.log(post);
            $.ajax({
                url: base_url + "api/fnUpdate/",
                data: post,
                type: "POST",
                dataType: "json",
                beforeSend: function (e) {
                    console.log('Updating..');
                },
                error: function (e) {
                    console.log(e.responseText);
                },
                success: function (data) {
                    console.log(data);
                    console.log('success');
                    location.reload();
                },
            });


        }
    });
    $(".onchanges").on("change", function () {

        if (!this.id) {
            alert('Please add unique ID, example id="hahaha-123" ')
        } else {
            console.log($('#' + this.id).data());

            post = {
                data: $('#' + this.id).data(),
                content: Base64.encode($('#' + this.id).val())
            }
            console.log(post);
            $.ajax({
                url: base_url + "api/fn_onchanges/",
                data: post,
                type: "POST",
                dataType: "json",
                beforeSend: function (e) {
                    console.log('Updating..');
                },
                error: function (e) {
                    console.log(e.responseText);
                },
                success: function (data) {
                    console.log(data);
                    console.log('success');
                    location.reload();
                },
            });


        }
    });
}

function sortable() {
    $(".fnSortable").sortable({
        handle: ".handle",
        update: function (event, ui) {
            var order = [];
            var obj;
            $('.fnSortable .handle').each(function (e) {
                obj = {
                    id: $(this).attr('id'),
                }
                order.push(obj);

            });

            post = {
                data: order,
            }

            $.ajax({
                url: base_url + "api/fn_sortable/",
                data: post,
                type: "POST",
                dataType: "json",
                beforeSend: function (e) {
                    console.log(order);
                },
                error: function (e) {
                    console.log(e.responseText);
                },
                success: function (data) {
                    console.log(data);
                },
            });

        }
    }).disableSelection();
}

function fnAddContent() {
    $(".fnAddContent").on("click", function () {
        console.log('fnAddContent');
        data = {
            id_pages: $(this).data('idpages')
        }
        console.log(data);
        $.ajax({
            url: base_url + "api/fn_addContent/",
            data: data,
            type: "POST",
            dataType: "json",
            beforeSend: function (e) {
            },
            error: function (e) {
                console.log(e.responseText);
            },
            success: function (data) {

                location.reload();
            },
        });
    });
}

function fnDelete() {
    $(".fnDelete").on("click", function () {
        post = {
            data: $(this).data('json')
        }
        console.log(post);
        if (confirm('Delete "' + post['data']['name'] + '" ')) {

            $.ajax({
                url: base_url + "api/fn_delete/",
                data: post,
                type: "POST",
                dataType: "json",
                beforeSend: function (e) {
                },
                error: function (e) {
                    console.log(e.responseText);
                },
                success: function (data) {
                    console.log(data);
                    location.reload();
                },
            });
        }


    });
}

function fnInsert() {
    $(".fnInsert").on("click", function () {
        post = {
            data: $(this).data('json')
        }
        console.log(post);
        $.ajax({
            url: base_url + "api/fn_insert/",
            data: post,
            type: "POST",
            dataType: "json",
            beforeSend: function (e) {
            },
            error: function (e) {
                console.log(e.responseText);
            },
            success: function (data) {
                console.log(data);
                location.reload();
            },
        });

    });
}

function fnRouter() {
    $(".fnRouter").on("click", function () {
        sendData = {
            function: "fnRouter",
            data: $('#' + this.id).data()
        }

        parent.postMessage(sendData, "*");
    });
}

function fnModal() {
    $(".fnModal").on("click", function () { 
        var section = $('#' + this.id).data()['json']['section'];

        sendData = {
            function: "fnModal",
            data: $('#' + this.id).data(),
            show: {
                section : section,
                label : $('#' + section).data(),
            },
        }
        
        parent.postMessage(sendData, "*");
    });
}

function richEditor() {

    tinymce.init({
        selector: '.fnText',
        plugins: ['save'],
        forced_root_block: false,
        formats: {
            removeformat: [
                {
                    selector: '*',
                    remove: 'all',
                },
            ]
        },
        menubar: false,
        toolbar: "save undo redo",
        inline: true,
        save_onsavecallback: function (data) {
            content = $('#' + data.id).html();
            data = {
                id: data.id,
                data: $('#' + data.id).data('json'),
                content: content
            }
            $.ajax({
                url: base_url + "api/fn_text/",
                data: data,
                type: "POST",
                dataType: "json",
                beforeSend: function (e) {
                    console.log('Updating..');
                },
                error: function (e) {
                    console.log(e.responseText);
                },
                success: function (data) {
                    console.log(data);
                    console.log('success');
                },
            });

        },


    })

    tinymce.init({
        selector: '.fnTextplus',
        inline: true,
        allow_unsafe_link_target: true,
        encoding: 'html',
        entity_encoding: "raw",
        menubar: false,
        visualblocks_default_state: true,
        convert_urls: false,
        verify_html: false,
        valid_elements: '*[*]',
        image_advtab: true,
        image_caption: true,
        plugins: [
            "advlist autolink link image lists charmap print  hr anchor pagebreak spellchecker toc",
            "searchreplace wordcount visualblocks  code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor importcss colorpicker textpattern codesample paste"
        ],
        add_unload_trigger: false,


        toolbar: "save undo redo  | bold italic  | alignleft aligncenter alignright alignjustify | link image media",
        file_picker_callback: mceElf.browser,

        save_onsavecallback: function (data) {
            content = $('#' + data.id).html();
            data = {
                data: $('#' + data.id).data('json'),
                content: Base64.encode(content)
            }

            $.ajax({
                url: base_url + "api/fn_richtext/",
                data: data,
                type: "POST",
                dataType: "json",
                beforeSend: function (e) {
                    console.log('Updating..');
                },
                error: function (e) {
                    console.log(e.responseText);
                },
                success: function (data) {
                    console.log(data);
                    console.log('success');
                },
            });

        },




    })


    tinymce.init({
        selector: '.fnRichtext',
        inline: true,
        allow_unsafe_link_target: true,
        encoding: 'html',
        entity_encoding: "raw",

        visualblocks_default_state: true,
        convert_urls: false,
        verify_html: false,
        valid_elements: '*[*]',
        image_advtab: true,
        image_caption: true,
        plugins: [
            "advlist autolink link image lists charmap print  hr anchor pagebreak spellchecker toc",
            "searchreplace wordcount visualblocks  code fullscreen insertdatetime  nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor importcss colorpicker textpattern codesample paste media "
        ],

        video_template_callback: function (data) {
            console.log(data);
            value = '<video width="' + data.width + '" height="' + data.height + '" controls>' +
                '<source src="' + data.source1 + '" type="video/mp4">' +
                'Your browser does not support HTML5 video.' +
                '</video>';
            return value;
        },

        toolbar: "save undo redo  | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media    ",
        file_picker_callback: mceElf.browser,


        //convert_urls: false,

        save_onsavecallback: function (data) {
            content = $('#' + data.id).html();
            data = {
                data: $('#' + data.id).data('json'),
                content: Base64.encode(content)
            }

            $.ajax({
                url: base_url + "api/fn_richtext/",
                data: data,
                type: "POST",
                dataType: "json",
                beforeSend: function (e) {
                    console.log('Updating..');
                },
                error: function (e) {
                    console.log(e.responseText);
                },
                success: function (data) {
                    console.log(data);
                    console.log('success');
                },
            });

        },

        setup: function (editor) {
            editor.ui.registry.addButton('helloworld', {
                // icon: 'image',
                text: "Hello Button",
                onAction: function () {
                    console.log(editor.windowManager);
                    editor.windowManager.open({
                        title: 'Hello World Example Plugin',
                        body: {
                            type: 'panel',
                            items: [{
                                type: 'input',
                                name: 'type',

                                flex: true
                            }],

                        },
                        onSubmit: function (api) {
                            // insert markup
                            // console.log(api);
                            value = '<video width="100%" height="auto" controls>' +
                                '<source src="' + api.getData().type + '" type="video/mp4">' +
                                'Your browser does not support HTML5 video.' +
                                '</video>';
                            editor.insertContent(value);

                            // close the dialog
                            // api.close();
                        },
                        buttons: [
                            {
                                text: 'Close',
                                type: 'cancel',
                                onclick: 'close'
                            },
                            {
                                text: 'Insert',
                                type: 'submit',
                                primary: true,
                                enabled: false
                            }
                        ]
                    });
                }
            });

        }


    });

}