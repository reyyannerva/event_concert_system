// Paralaks Efekti
document.addEventListener("scroll", function () {
    const bg = document.querySelector('.dynamic-bg');
    if (bg) {
        let scrollY = window.scrollY;
        bg.style.backgroundPositionY = -(scrollY * 0.5) + "px";
    }
});

// Buton Hover Animasyonu (Ekstra Görsellik)
document.querySelectorAll('.button-dynamic').forEach(button => {
    button.addEventListener('mouseover', function () {
        button.style.transform = 'scale(1.1)';
        button.style.boxShadow = '0px 5px 15px rgba(0, 0, 0, 0.3)';
    });
    button.addEventListener('mouseout', function () {
        button.style.transform = 'scale(1)';
        button.style.boxShadow = 'none';
    });
});

// Dinamik Başlık Efekti (Gradient Hareketi)
window.addEventListener("load", function () {
    const header = document.querySelector('.dynamic-header');
    if (header) {
        let colors = ['#56ccf2', '#2f80ed', '#ff758c'];
        let i = 0;
        setInterval(() => {
            header.style.background = `linear-gradient(to right, ${colors[i]}, ${colors[(i + 1) % colors.length]})`;
            i = (i + 1) % colors.length;
        }, 3000);
    }
});

// Özel Harita İkonu
const customIcon = L.icon({
    iconUrl: 'marker-icon.png', // Özel bir simge URL'si
    iconSize: [30, 40],
});
const marker = L.marker([latitude, longitude], { icon: customIcon }).addTo(map);

// Favorilere Ekleme İşlevi
function toggleFavorite(button, eventId) {
    fetch(`add_favorites.php?event_id=${eventId}`, { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Buton sınıfını güncelle
                button.classList.toggle('favorited');
                button.textContent = button.classList.contains('favorited') 
                    ? '❤️ Favorilerden Çıkar' 
                    : '🤍 Favorilere Ekle';
            } else {
                alert(data.message || 'Favorilere ekleme sırasında bir hata oluştu.');
            }
        })
        .catch(error => console.error('Hata:', error));
}

// Tooltip İşlevi (Dinamik Tooltipler için)
document.querySelectorAll('[data-tooltip]').forEach(element => {
    element.addEventListener('mouseenter', function () {
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.textContent = element.getAttribute('data-tooltip');
        document.body.appendChild(tooltip);

        const rect = element.getBoundingClientRect();
        tooltip.style.left = `${rect.left + window.pageXOffset}px`;
        tooltip.style.top = `${rect.top + window.pageYOffset - tooltip.offsetHeight}px`;

        element.addEventListener('mouseleave', function () {
            tooltip.remove();
        });
    });
});

// Otomatik Öneri İşlevi (Autocomplete)
function showSuggestions(input, type) {
    const term = input.value;
    const suggestionsBox = document.getElementById(`${input.id}-suggestions`);

    if (term.length < 2) {
        suggestionsBox.innerHTML = '';
        return;
    }

    fetch(`get_suggestions.php?type=${type}&term=${term}`)
        .then(response => response.json())
        .then(data => {
            suggestionsBox.innerHTML = '';
            data.forEach(item => {
                const div = document.createElement('div');
                div.textContent = item;
                div.onclick = () => {
                    input.value = item;
                    suggestionsBox.innerHTML = '';
                };
                suggestionsBox.appendChild(div);
            });
        })
        .catch(error => console.error('Hata:', error));
}

// Scrolla Göre Menü Gizleme (Dinamik Navbar)
let lastScrollY = window.scrollY;
document.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;

    if (window.scrollY > lastScrollY) {
        navbar.style.top = '-70px'; // Yukarı kaydırırken gizle
    } else {
        navbar.style.top = '0'; // Aşağı kaydırırken göster
    }
    lastScrollY = window.scrollY;
});

// Etkinlik Kartlarına Animasyon (Fade-In)
const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('fade-in');
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.event-card').forEach(card => {
    observer.observe(card);
});

// Dinamik Başlık Metni (Sayfa Yüklendiğinde)
document.addEventListener("DOMContentLoaded", () => {
    const dynamicHeader = document.querySelector('.dynamic-header');
    if (dynamicHeader) {
        dynamicHeader.textContent = "En Güzel Etkinlikler Seni Bekliyor!";
    }
});

// Filtreleme Butonu İşlevi
document.querySelector('.filter-button').addEventListener('click', () => {
    const searchTerm = document.querySelector('.filter-input').value;
    if (searchTerm.trim()) {
        window.location.href = `search_event.php?query=${searchTerm}`;
    }
});
