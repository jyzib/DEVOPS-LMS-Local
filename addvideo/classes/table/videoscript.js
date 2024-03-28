

    window.addEventListener('load', () => {
        const customCopyButtons = document.querySelectorAll('.custom-copy');
        console.log(customCopyButtons);
const copytext = (n)=>{
    const str = `<a href="https://yislms.com/devops/yatharthriti/local/coursedetail.php?id=${n}">
    <video src="https://yislms.com/devops/yatharthriti${n}"></video>
</a>`

navigator.clipboard.writeText(str)
.then(() => {
    console.log('Text successfully copied to clipboard');
})
.catch((err) => {
    console.error('Unable to copy text to clipboard', err);
});
console.log(str)
}
        customCopyButtons.forEach((button) => {
            button.addEventListener('click', (e) => {
                
                // console.log(data);
                copytext(e.target.dataset.videopath)
// console.log(e.target.dataset.videopath)
       
            });
        });
    });
