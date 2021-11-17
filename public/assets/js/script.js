document.querySelectorAll('.updateButton').forEach(item => {
    item.addEventListener('click', event => {
       event.target.parentNode.parentNode.parentNode.children[2].style.display = "block";
    })
  })


