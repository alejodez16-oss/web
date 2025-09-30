function exportarPDF() {
    // Configuración de jsPDF
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'pt', 'a4');

    // Captura la tabla con html2canvas
    const tabla = document.getElementById('tabla-usuarios');
    
    html2canvas(tabla, {
        scale: 2, // Mejor calidad
        logging: false,
        useCORS: true
    }).then((canvas) => {
        const imgData = canvas.toDataURL('image/png');
        
        // Ajusta el tamaño de la imagen al PDF
        const imgWidth = doc.internal.pageSize.getWidth() - 40; // Margen
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        
        doc.addImage(imgData, 'PNG', 20, 20, imgWidth, imgHeight);
        doc.save('proveedores_' + new Date().toLocaleDateString() + '.pdf');
    });
}
