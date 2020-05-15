<main>
    <?php
        date_default_timezone_set('Europe/Minsk');
    
        $mysqli = new mysqli("localhost", getenv('DEV_USER'), getenv('DEV_USER_PASSWORD'), "visitors");
        if ($mysqli->connect_errno) {
            echo "Failed to connect mysqli: " . $mysqli->connect_error;
        }

        function fetchForLastPeriod(object $mysqli, int $hours): array
        {
            $hour = 3600;
            $startDate = date("Y-m-d H:i:s", time() - $hours * $hour);
            $endDate = date("Y-m-d H:i:s");

            $sqlExpression = "SELECT dateString FROM visitors WHERE dateString BETWEEN ? AND ?";
            $fetchDatesStatement = $mysqli->prepare($sqlExpression);
            
            $periodData = array();
            if ($fetchDatesStatement) {
                $fetchDatesStatement->bind_param("ss", $startDate, $endDate);
                $fetchDatesStatement->execute();
                
                $dateRow = null;
                $fetchDatesStatement->bind_result($dateRow);
    
                while ($fetchDatesStatement->fetch()) {
                    array_push($periodData, $dateRow);
                }
            }
            
            return $periodData;
        }

        function sortForLastPeriod(array $rawPeriodData, int $hours, int $offset, string $titleFormat): array
        {
            $hour = 3600;
            $sorted = array();

            $beginDate = date("Y-m-d H:i:s", time() - ($hours) * $hour);

            $currentVisitIndex = 0;
            $offsetInHours = 0;
            for ($hourOffsetIndex = 0; $hourOffsetIndex < $hours; $hourOffsetIndex += $offset) {
                $startDate = date("Y-m-d H:i:s", strtotime($beginDate) + $offsetInHours * $hour);
                $endDate = date("Y-m-d H:i:s", strtotime($startDate) + $offset * $hour);

                $sorted[$hourOffsetIndex] = [
                    'title' => date($titleFormat, strtotime($startDate)).' - '.date($titleFormat, strtotime($endDate)),
                    'number' => 0
                ];
                
                while ($currentVisitIndex < count($rawPeriodData) &&
                       $rawPeriodData[$currentVisitIndex] >  $startDate &&
                       $rawPeriodData[$currentVisitIndex] <= $endDate
                    ) {
                    $sorted[$hourOffsetIndex] = [
                        'title' => date($titleFormat, strtotime($startDate)).' - '.date($titleFormat, strtotime($endDate)),
                        'number' => $sorted[$hourOffsetIndex]['number'] + 1
                    ];
                    $currentVisitIndex++;
                }

                $offsetInHours += $offset;
            }

            return $sorted;
        }

        function printChartRow(array $dataSet): string
        {
            return "
            <div class='chart-line'>
                <span class='chart-col-title'>".$dataSet['title']."</span>
                <div style='width:".($dataSet['number'] * 10)."px;' class='chart-col'>".$dataSet['number']."</div>
            </div>";
        }

        function printChart(array $sortedPeriodData): string
        {
            $resultStr = '';
            foreach ($sortedPeriodData as $dataSet) {
                $resultStr .= printChartRow($dataSet);
            }
            return $resultStr;
        }
    ?>    

    <h4>Last 24-hours stats (by hours):</h4>
    <div class="chart-container">
        <?= printChart(sortForLastPeriod(fetchForLastPeriod($mysqli, 24), 24, 1, 'H:i'));?>
    </div>

    <h4>Last 7 days stats (by days):</h4>
    <div class="chart-container">
        <?= printChart(sortForLastPeriod(fetchForLastPeriod($mysqli, 24 * 7), 24 * 7, 24, 'm-d H:i'));?>
    </div>

    <h4>Last 30 days stats (by days):</h4>
    <div class="chart-container">
        <?= printChart(sortForLastPeriod(fetchForLastPeriod($mysqli, 24 * 30), 24 * 30, 24, 'm-d H:i'));?>
    </div>

    <h4>Last 365 days stats (by months):</h4>
    <div class="chart-container">
        <?= printChart(sortForLastPeriod(fetchForLastPeriod($mysqli, 24 * 365), 24 * 365, 24 * 31, 'Y-m-d H:i'));?>
    </div>
</main>

