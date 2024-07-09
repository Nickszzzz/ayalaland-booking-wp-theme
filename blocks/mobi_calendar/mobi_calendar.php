<div>
<div id="demo-calendar"></div>
<div id="demo-calendar-skeleton" ></div>
<div id="demo-range"></div>
<div id="demo-range-skeleton"></div>
</div>
<div class="legend">
    <span class="demo-calendar-past">past</span>
    <span class="demo-calendar-today">available</span>
    <span class="demo-calendar-booked">not available</span>
</div>

<style>
  #demo-calendar-skeleton {
    width: 100%;
    height: 300px; /* Adjust height as needed */
    background-color: #e0e0e0;
    border-radius: 8px;
    animation: pulse 1.5s infinite;
  }

  @keyframes pulse {
    0% {
      background-color: #e0e0e0;
    }
    50% {
      background-color: #f0f0f0;
    }
    100% {
      background-color: #e0e0e0;
    }
  }

  #demo-calendar.loading div {
    cursor: wait;
  }
</style>