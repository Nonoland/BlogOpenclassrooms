/*!
* Start Bootstrap - Clean Blog v6.0.8 (https://startbootstrap.com/theme/clean-blog)
* Copyright 2013-2022 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-clean-blog/blob/master/LICENSE)
*/
window.addEventListener('DOMContentLoaded', () => {
    let scrollPos = 0;
    const mainNav = document.getElementById('mainNav');
    const headerHeight = mainNav.clientHeight;
    window.addEventListener('scroll', function() {
        const currentTop = document.body.getBoundingClientRect().top * -1;
        if ( currentTop < scrollPos) {
            // Scrolling Up
            if (currentTop > 0 && mainNav.classList.contains('is-fixed')) {
                mainNav.classList.add('is-visible');
            } else {
                mainNav.classList.remove('is-visible', 'is-fixed');
            }
        } else {
            // Scrolling Down
            mainNav.classList.remove(['is-visible']);
            if (currentTop > headerHeight && !mainNav.classList.contains('is-fixed')) {
                mainNav.classList.add('is-fixed');
            }
        }
        scrollPos = currentTop;
    });

    //Comment Response
    const commentForm = document.querySelector('.comment_form');
    const commentsAnswer = document.querySelectorAll(".comment a.comment_answer");
    commentsAnswer.forEach((answer) => {
        answer.addEventListener('click', (event) => {
            event.preventDefault();

            if ("open" in answer.dataset) {
                return;
            }
            answer.dataset.open = true;

            const comment = document.getElementById(`comment_${answer.dataset.commentId}`);
            if (comment === undefined) {
                return;
            }

            const newCommentForm = commentForm.cloneNode(true);
            newCommentForm.classList.add('child_comment');
            newCommentForm.querySelector('input[name="comment-id"]').value = answer.dataset.commentId;

            comment.insertAdjacentElement('afterend', newCommentForm );
        })
    })
})
