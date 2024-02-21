document.querySelectorAll('select').forEach((select) => {
    const options = [];
    select.querySelectorAll('option').forEach((option) => {
        if (options.includes(option.value)) option.remove();
        else options.push(option.value);
    })
});