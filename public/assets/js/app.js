var selectedOptions = {
    foundation: '',
    dd: '',
    roof: ''
};

function toggleSuboptions(element) {
    var suboptions = element.parentNode.querySelector('.suboptions');
    var subsuboptions = element.parentNode.querySelector('.subsuboptions');

    if (suboptions) {
        var allSuboptions = document.querySelectorAll('.suboptions');
        allSuboptions.forEach(function(suboption) {
            if (suboption !== suboptions) {
                suboption.style.display = 'none';
            }
        });
        suboptions.style.display = suboptions.style.display === 'none' ? 'block' : 'none';
    }

    if (subsuboptions) {
        var allSubsuboptions = document.querySelectorAll('.subsuboptions');
        allSubsuboptions.forEach(function(subsuboption) {
            if (subsuboption !== subsuboptions) {
                subsuboption.style.display = 'none';
            }
        });
        subsuboptions.style.display = subsuboptions.style.display === 'none' ? 'block' : 'none';
    }
  }


function updateTotalPrice() {
    var totalPrice = 999; // Start with the base price
    console.log(totalPrice);
    var selectedLabels = document.querySelectorAll('.btn-outline-primary.active');
    selectedLabels.forEach(function(label) {
        var price = parseFloat(label.getAttribute('data-price'));
        console.log(price);
        if (!isNaN(price)) {
            totalPrice += price;
        }
    });

    document.getElementById('totalPrice').textContent = totalPrice.toFixed(2);
}



