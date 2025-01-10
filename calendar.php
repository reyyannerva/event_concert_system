<?php
require 'config.php'; // Veritabanı bağlantısı

// Etkinlikleri al
$stmt = $conn->prepare("
    SELECT 
        e.id AS event_id, 
        e.name AS event_name, 
        e.event_date AS event_date, 
        e.event_time AS event_time, 
        v.name AS venue_name 
    FROM events e
    JOIN venues v ON e.venue_id = v.id
");
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etkinlik Takvimi</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #1c92d2, #f2fcfe);
            margin: 0;
            padding: 0;
        }
        header {
            text-align: center;
            padding: 20px;
            background: linear-gradient(to right, #ff758c, #ff7eb3);
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }
        #calendar {
            max-width: 900px;
            margin: 20px auto;
            padding: 10px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }
        .tooltip {
            position: absolute;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-size: 0.9rem;
            display: none;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <header>
        📅 Etkinlik Takvimi
    </header>
    <div id="calendar"></div>
    <div id="tooltip" class="tooltip"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const tooltip = document.getElementById('tooltip');

            // PHP'den gelen etkinlik verilerini alın
            const events = <?php echo json_encode($events); ?>.map(event => ({
                title: event.event_name,
                start: `${event.event_date}T${event.event_time}`,
                url: `event_details.php?id=${event.event_id}`,
                description: event.venue_name
            }));

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: events,
                eventMouseEnter: function(info) {
                    // Tooltip göster
                    tooltip.style.display = 'block';
                    tooltip.innerHTML = `<strong>${info.event.title}</strong><br>${info.event.extendedProps.description}`;
                    tooltip.style.left = `${info.jsEvent.pageX + 10}px`;
                    tooltip.style.top = `${info.jsEvent.pageY + 10}px`;
                },
                eventMouseLeave: function() {
                    // Tooltip gizle
                    tooltip.style.display = 'none';
                },
                eventClick: function(info) {
                    if (info.event.url) {
                        window.open(info.event.url, '_blank'); // Etkinlik detay sayfasına yönlendir
                        info.jsEvent.preventDefault();
                    }
                }
            });

            calendar.render();
        });
    </script>
</body>
</html>
