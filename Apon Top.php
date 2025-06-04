<?php
// Developer By Murad 🏆
$host = "localhost";
$user = "developerbymurad_Apps_Sell_1";
$password = "developerbymurad_Apps_Sell_1";
$dbname = "developerbymurad_Apon";

// Create connection
$mysql = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// Query to get top 20 players by winning
$sql = "SELECT username, winning FROM users ORDER BY winning DESC LIMIT 100";
$result = $mysql->query($sql);

if ($result === false) {
    die("Error executing query: " . $mysql->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🏆 Top Players Leaderboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0c0c1e 0%, #1a1a3e 50%, #0c0c1e 100%);
            color: #fff;
            font-family: 'Rajdhani', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Animated Background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .bg-animation::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(0,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        .floating-particles {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .particle {
            position: absolute;
            background: #00ffff;
            border-radius: 50%;
            opacity: 0.6;
            animation: float 6s ease-in-out infinite;
        }

        .particle:nth-child(1) { width: 4px; height: 4px; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 6px; height: 6px; left: 20%; animation-delay: 1s; }
        .particle:nth-child(3) { width: 3px; height: 3px; left: 30%; animation-delay: 2s; }
        .particle:nth-child(4) { width: 5px; height: 5px; left: 40%; animation-delay: 3s; }
        .particle:nth-child(5) { width: 4px; height: 4px; left: 50%; animation-delay: 4s; }
        .particle:nth-child(6) { width: 6px; height: 6px; left: 60%; animation-delay: 5s; }
        .particle:nth-child(7) { width: 3px; height: 3px; left: 70%; animation-delay: 0.5s; }
        .particle:nth-child(8) { width: 5px; height: 5px; left: 80%; animation-delay: 1.5s; }
        .particle:nth-child(9) { width: 4px; height: 4px; left: 90%; animation-delay: 2.5s; }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        /* Header Styling */
        .header {
            text-align: center;
            margin-bottom: 40px;
            animation: slideDown 1s ease-out;
        }

        .main-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 4rem;
            font-weight: 900;
            background: linear-gradient(45deg, #00ffff, #ff00ff, #ffff00, #00ffff);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease-in-out infinite;
            text-shadow: 0 0 30px rgba(0,255,255,0.5);
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 1.2rem;
            color: #00ffff;
            opacity: 0.8;
            letter-spacing: 2px;
        }

        .trophy-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
        }

        .trophy-icon {
            font-size: 2rem;
            animation: bounce 2s infinite;
        }

        .trophy-icon:nth-child(1) { color: #ffd700; animation-delay: 0s; }
        .trophy-icon:nth-child(2) { color: #00ffff; animation-delay: 0.5s; }
        .trophy-icon:nth-child(3) { color: #ff00ff; animation-delay: 1s; }

        /* Refresh Button */
        .refresh-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .refresh-btn {
            background: linear-gradient(45deg, #00ffff, #0077ff, #00ffff);
            background-size: 200% 200%;
            border: none;
            color: white;
            padding: 15px 40px;
            font-size: 18px;
            font-weight: 700;
            font-family: 'Orbitron', sans-serif;
            border-radius: 50px;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(0, 255, 255, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            animation: pulse 2s infinite;
        }

        .refresh-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 35px rgba(0, 255, 255, 0.6);
            animation: gradientShift 1s ease infinite;
        }

        .refresh-btn:active {
            transform: translateY(0) scale(0.98);
        }

        .refresh-btn i {
            margin-right: 10px;
            transition: transform 0.3s ease;
        }

        .refresh-btn:hover i {
            transform: rotate(360deg);
        }

        /* Leaderboard Table */
        .leaderboard-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            border: 1px solid rgba(0, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 1s ease-out 0.5s both;
        }

        .table-header {
            background: linear-gradient(135deg, rgba(0, 255, 255, 0.2), rgba(255, 0, 255, 0.2));
            padding: 20px;
            border-bottom: 1px solid rgba(0, 255, 255, 0.3);
        }

        .table-header-row {
            display: grid;
            grid-template-columns: 1fr 2fr 2fr 1fr;
            gap: 20px;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            font-size: 1.2rem;
            text-align: center;
            color: #00ffff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .table-body {
            padding: 20px;
        }

        .player-row {
            display: grid;
            grid-template-columns: 1fr 2fr 2fr 1fr;
            gap: 20px;
            align-items: center;
            padding: 15px 20px;
            margin-bottom: 10px;
            border-radius: 15px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out both;
            border: 1px solid transparent;
        }

        .player-row:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 30px rgba(0, 255, 255, 0.2);
        }

        .player-row::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .player-row:hover::before {
            left: 100%;
        }

        /* Rank Styling */
        .rank {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .rank-icon {
            font-size: 1.8rem;
        }

        /* Player Name */
        .player-name {
            font-size: 1.3rem;
            font-weight: 600;
            text-align: center;
        }

        /* Amount */
        .amount {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            text-align: center;
            color: #00ff88;
        }

        /* Status Badge */
        .status {
            text-align: center;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Rank-specific styling */
        .gold {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.2), rgba(255, 165, 0, 0.2));
            border-color: rgba(255, 215, 0, 0.5);
            color: #ffd700;
        }

        .silver {
            background: linear-gradient(135deg, rgba(192, 192, 192, 0.2), rgba(169, 169, 169, 0.2));
            border-color: rgba(192, 192, 192, 0.5);
            color: #c0c0c0;
        }

        .bronze {
            background: linear-gradient(135deg, rgba(205, 127, 50, 0.2), rgba(184, 115, 51, 0.2));
            border-color: rgba(205, 127, 50, 0.5);
            color: #cd7f32;
        }

        .regular {
            background: linear-gradient(135deg, rgba(0, 255, 255, 0.1), rgba(0, 119, 255, 0.1));
            border-color: rgba(0, 255, 255, 0.3);
            color: #00ffff;
        }

        .elite-badge {
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            color: #000;
        }

        .active-badge {
            background: linear-gradient(45deg, #00ffff, #0077ff);
            color: #fff;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        /* Animations */
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes slideDown {
            0% { opacity: 0; transform: translateY(-50px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideUp {
            0% { opacity: 0; transform: translateY(50px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        @keyframes pulse {
            0% { box-shadow: 0 8px 25px rgba(0, 255, 255, 0.4); }
            50% { box-shadow: 0 8px 25px rgba(0, 255, 255, 0.6); }
            100% { box-shadow: 0 8px 25px rgba(0, 255, 255, 0.4); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-title { font-size: 2.5rem; }
            .table-header-row, .player-row {
                grid-template-columns: 1fr 2fr 1.5fr 1fr;
                gap: 10px;
            }
            .container { padding: 10px; }
        }

        @media (max-width: 480px) {
            .main-title { font-size: 2rem; }
            .table-header-row, .player-row {
                grid-template-columns: 1fr;
                gap: 5px;
                text-align: center;
            }
            .refresh-btn { padding: 12px 30px; font-size: 16px; }
        }

        /* Loading Animation */
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .spinner {
            border: 4px solid rgba(0, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 4px solid #00ffff;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation">
        <div class="floating-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
    </div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 class="main-title">TOP PLAYERS</h1>
            <p class="subtitle">Hall of Fame Leaderboard</p>
            <div class="trophy-icons">
                <i class="fas fa-trophy trophy-icon"></i>
                <i class="fas fa-crown trophy-icon"></i>
                <i class="fas fa-medal trophy-icon"></i>
            </div>
        </div>

        <!-- Refresh Button -->
        <div class="refresh-container">
            <button class="refresh-btn" onclick="refreshPage()">
                <i class="fas fa-sync-alt"></i>
                Refresh Data
            </button>
        </div>

        <!-- Loading Animation -->
        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p style="margin-top: 10px;">Refreshing data...</p>
        </div>

        <!-- Leaderboard -->
        <div class="leaderboard-container" id="leaderboard">
            <div class="table-header">
                <div class="table-header-row">
                    <div>Rank</div>
                    <div>Player</div>
                    <div>Amount</div>
                    <div>Status</div>
                </div>
            </div>

            <div class="table-body">
                <?php
                $rank = 1;
                while ($row = $result->fetch_assoc()) {
                    $class = 'regular';
                    $rankIcon = '<i class="fas fa-star rank-icon"></i>';
                    $statusBadge = '<span class="status-badge active-badge">Active</span>';
                    
                    if ($rank == 1) {
                        $class = 'gold';
                        $rankIcon = '<i class="fas fa-crown rank-icon" style="color: #ffd700;"></i>';
                        $statusBadge = '<span class="status-badge elite-badge">Champion</span>';
                    } elseif ($rank == 2) {
                        $class = 'silver';
                        $rankIcon = '<i class="fas fa-medal rank-icon" style="color: #c0c0c0;"></i>';
                        $statusBadge = '<span class="status-badge elite-badge">Runner-up</span>';
                    } elseif ($rank == 3) {
                        $class = 'bronze';
                        $rankIcon = '<i class="fas fa-award rank-icon" style="color: #cd7f32;"></i>';
                        $statusBadge = '<span class="status-badge elite-badge">Third Place</span>';
                    }

                    echo "<div class='player-row $class' style='animation-delay: " . ($rank * 0.1) . "s;'>";
                    echo "<div class='rank'>$rankIcon <span>#$rank</span></div>";
                    echo "<div class='player-name'>" . htmlspecialchars($row['username']) . "</div>";
                    echo "<div class='amount'>" . number_format($row['winning']) . " TK</div>";
                    echo "<div class='status'>$statusBadge</div>";
                    echo "</div>";
                    $rank++;
                }
                ?>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><i class="fas fa-clock"></i> Last updated: <?php echo date('Y-m-d H:i:s'); ?></p>
            <p>Developed by Murad 🏆</p>
        </div>
    </div>

    <script>
        function refreshPage() {
            // Show loading animation
            document.getElementById('loading').style.display = 'block';
            document.getElementById('leaderboard').style.opacity = '0.5';
            
            // Simulate loading time
            setTimeout(function() {
                // Show success message
                showToast('Data refreshed successfully!', 'success');
                
                // Reload page
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }, 1500);
        }

        function showToast(message, type) {
            // Create toast element
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? 'linear-gradient(45deg, #00ff88, #00cc6a)' : 'linear-gradient(45deg, #ff4757, #ff3742)'};
                color: white;
                padding: 15px 25px;
                border-radius: 10px;
                font-family: 'Orbitron', sans-serif;
                font-weight: 600;
                box-shadow: 0 10px 25px rgba(0,0,0,0.3);
                z-index: 1000;
                animation: slideInRight 0.5s ease-out;
            `;
            toast.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
            
            // Add animation keyframes
            if (!document.querySelector('#toast-styles')) {
                const style = document.createElement('style');
                style.id = 'toast-styles';
                style.textContent = `
                    @keyframes slideInRight {
                        0% { transform: translateX(100%); opacity: 0; }
                        100% { transform: translateX(0); opacity: 1; }
                    }
                    @keyframes slideOutRight {
                        0% { transform: translateX(0); opacity: 1; }
                        100% { transform: translateX(100%); opacity: 0; }
                    }
                `;
                document.head.appendChild(style);
            }
            
            document.body.appendChild(toast);
            
            // Remove toast after 3 seconds
            setTimeout(() => {
                toast.style.animation = 'slideOutRight 0.5s ease-out';
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 500);
            }, 3000);
        }

        // Add scroll animations
        window.addEventListener('scroll', function() {
            const elements = document.querySelectorAll('.player-row');
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < window.innerHeight - elementVisible) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        });

        // Initialize animations on page load
        window.addEventListener('load', function() {
            document.body.style.opacity = '1';
        });
    </script>
</body>
</html>