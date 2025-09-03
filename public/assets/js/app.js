(function(){
const statusInputs = document.querySelectorAll('input[name="work_status"]');
const expFields = document.querySelectorAll('[data-experienced]');


function render(){
const val = document.querySelector('input[name="work_status"]:checked')?.value || 'fresher';
expFields.forEach(el => {
el.style.display = (val === 'experienced') ? 'block' : 'none';
});
}


statusInputs.forEach(i => i.addEventListener('change', render));
render();
})();