getMois = (elem) => {
    {
        $.ajax({
            type: 'GET',
            url: '/frais/mois',
            data: {
                id: $(elem).val(),
            },
            dataType: 'json',
            success: function(data) {
                document.getElementById("mois").innerHTML = "";
                $.each(data, function(i, obj) {
                    var option = document.createElement('option');
                    option.value = obj.mois;
                    option.innerHTML = obj.mois.substr(4)+ '/'+  obj.mois.substr(0,4);
                    document.getElementById("mois").appendChild(option);
                })
            },
            error: function(data) {
                alert('Récupération des donées impossible');
            }
        });

    } }
