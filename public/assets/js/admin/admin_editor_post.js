let input_post_body = document.querySelector('input[name="post_body"]');
let btn_post_submit = document.querySelector('button[name="post_submit"]');
let form = document.getElementById('form_post');
let editor_config = {
    holder: 'post_body',
    tools: {
        header: {
            class: Header,
            inlineToolbar: ['link']
        },
        quote: {
            class: Quote,
            inlineToolbar: true,
            config: {
                quotePlaceholder: 'Votre citation',
                captionPlaceholder: 'Auteur de la citation'
            }
        },
        embed: {
            class: Embed,
            config: {
                services: {
                    youtube: true
                }
            }
        },
        warning: {
            class: Warning,
            inlineToolbar: true
        }
    }
};

document.addEventListener('DOMContentLoaded', () => {

    if (input_post_body.value) {
        editor_config['data'] = {
            time: 10000,
            blocks: JSON.parse(input_post_body.value),
            version: ""
        };

        input_post_body.value = '';
    }

    const editor = new EditorJS(editor_config);

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        editor.save().then((outputData) => {
            input_post_body.value = JSON.stringify(outputData.blocks);

            let formData = new FormData(document.getElementById('form_post'));

            let url = form.dataset.action === 'new' ? '/admin/ajax/posts/new' : '/admin/ajax/posts/edit/'+form.dataset.id;

            fetch(
                url,
                {
                    method: 'POST',
                    body: formData
                }
            ).then((response) => {
                return response.json();
            }).then((response) => {
                window.location.replace(
                    form.dataset.retargetUrl
                );
            });
        });
    });
});
