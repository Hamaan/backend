    $(
        function()
        {
            // При выборе квартиры
            $("#ZoneSelect").change
            (
                function()
                {
                    // Если выбран не первый пункт (т.е. не "Выберите")
                    if( $(this).val() != 0 )
                    {
                        // Делаем к серверу POST запрос
                        $.post
                        (
                            "loader.php", // Куда отправляем запрос
                            
                            {type: "TownSelect", zoneID: $(this).val()}, // Данные для передачи
                            
                            // После успешного зароса
                            function(data)
                            {
                                // Вставляем список в SELECT
                                $("#TownSelect").html(data[0]);
                                
                                // Вставляем информацию в блок
                                //$("#info_kv").html(data[1]);
                                
                                // Делаем список доступным
                                $("#TownSelect").removeAttr("disabled");
                            },
                            "json" // Указываем, что данные придёт в JSON формате
                        );
                    }
                    else
                    {
                        // Удаляем список в SELECT
                        $("#TownSelect").html('<option>Выберите зону</option>');
                        
                        // Делаем список недоступным
                        $("#TownSelect").attr("disabled", "disabled");
                        
                        // Удаляем информацию из блока
                        $("#information").html("");
                    }
                }
            );
            
            
        }
    );
 