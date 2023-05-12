const editor_config = {
    data: {},
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
    const input_post_body = document.querySelector('input[name="post_body"]');
    const form = document.getElementById('form_post');
    const form_submit = form.querySelector("button[type=submit]");

    //Si edit
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

            const formData = new FormData(form);

            const url = form.dataset.action === 'new' ? '/admin/ajax/posts/new' : '/admin/ajax/posts/edit/'+form.dataset.id;

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

    //Image preview
    const image_input = document.getElementById("post_image");
    const image_preview = document.getElementById("post_image_preview");
    image_input.addEventListener('change', (event) => {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                const imageUrl = e.target.result;
                image_preview.src = imageUrl;
            }

            reader.readAsDataURL(file);
        }
    });

    //Slug test
    const slug_input = document.getElementById("post_slug");
    const current_slug = slug_input.value;
    const slug_error = document.getElementById("slug_error");
    slug_input.addEventListener("change", (event) => {

        if (slug_input.value === current_slug) {
            return;
        }

        fetch(
            "/admin/ajax/posts/slug/" + slug_input.value,
            {
                method: 'POST',
            }
        ).then((response) => {
            return response.json();
        }).then((response) => {
            if (!response) {
                slug_error.textContent = "Attention ! Le slug est déjà présent, impossible de le modifier."
                form_submit.disabled = true;
            } else {
                slug_error.textContent = "";
                form_submit.disabled = false;
            }
        });
    });
});
