<style>
/* Custom CSS for Links Page */
.card {
    transition: transform 0.2s ease;
    border: 1px solid rgba(0,0,0,0.1);
}

.card:hover {
    transform: translateY(-2px);
    background-color: #f8f9fa;
}

.card i {
    font-size: 1.1rem;
    color: #666;
    width: 24px;
    text-align: center;
}

.card-text {
    font-size: 0.85rem;
    line-height: 1.4;
}

.stretched-link::after {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1;
    content: "";
}
</style>
