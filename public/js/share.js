/*
    Twitter:
    https://twitter.com/share?url=[post-url]&text=[post-title]


    Facebook:
    https://www.facebook.com/sharer.php?u=[post-url]

*/
const facebookBtn = document.querySelector('.facebook-btn');
const twitterBtn = document.querySelector('.twitter-btn');

function init() {
    let url = encodeURI(document.location.href);
    let postTitle = encodeURI("Hi everyone, please check it out.");

    facebookBtn.setAttribute("href", `https://www.facebook.com/sharer.php?u=${url}`);
    twitterBtn.setAttribute("href", `https://twitter.com/share?url=${url}&text=${postTitle}`);
}

init();