 </main>
    
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-3">Tarif Lezzetleri</h5>
                    
                    <p>Türk ve dünya mutfağından en lezzetli tarifler, püf noktaları ve daha fazlasını bulabileceğiniz yemek tarifleri platformu.</p>
                    
                </div>

                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-3">Kategoriler</h5>
                    <ul class="list-unstyled">
                        <?php 
                        $footer_kategoriler = kategorileri_getir();
                        foreach($footer_kategoriler as $kategori): 
                        ?>
                        <li class="mb-2"><a href="kategori.php?id=<?php echo $kategori['id']; ?>" class="text-white text-decoration-none"><i class="fas fa-angle-right me-1"></i> <?php echo $kategori['kategori_adi']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
    </footer>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="assets/js/custom.js"></script>
</body>
</html>