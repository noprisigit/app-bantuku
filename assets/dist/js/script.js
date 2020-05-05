const flashData = $('.flash-data').data('flashdata');
const title = $('.flash-data').data('title');

console.log(flashData);
console.log(title);

if (flashData) {
    Swal.fire({
        title: title,
        text: flashData,
        icon: 'success'
    });
}