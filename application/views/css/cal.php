<style>
.overlay {
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 10;
  background-color: rgba(0, 0, 0, 0.3);
  /*dim the background*/
}
</style>

<style>
    .weather-widget {
        font-size: 14px;
    }

    .weather-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .weather-column {
        background-color: #f5f5f5;
        border-radius: 4px;
        padding: 20px;
    }

    .location-name {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .weather-row {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }

    .weather-label {
        display: flex;
        align-items: center;
        margin-right: 10px;
    }

    .weather-label i {
        margin-right: 5px;
    }

    .weather-value {
        font-weight: bold;
    }

    .alert {
        padding: 10px;
        background-color: #f8d7da;
        color: #721c24;
        border-radius: 4px;
    }
</style>

<style>
.canceled-event {
    position: relative;
}

.canceled-event::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom right,
        transparent calc(50% - 2px),
        red 50%,
        transparent calc(50% + 2px));
    pointer-events: none;
    z-index: 1;
}

/* Optional: Add a semi-transparent overlay to fade the content */
.canceled-event::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.3);
    pointer-events: none;
}
</style>
