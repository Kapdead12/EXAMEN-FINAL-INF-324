using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Drawing.Imaging;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace formModII
{
    public partial class Form1 : Form
    {
        //int R, G, B;
        Bitmap baseImage;
        Bitmap compareImage;
        private int umbral = 30;

        public Form1()
        {
            InitializeComponent();
           
        }


        //===================NORMALIZAR LAS IMAGENES====================
        private Bitmap ResizeImage(Image originalImage, int width, int height)
        {
            Bitmap resizedImage = new Bitmap(width, height);
            using (Graphics graphics = Graphics.FromImage(resizedImage))
            {
                // Configuramos calidad de renderizado para mantener la mejor resolución posible
                graphics.InterpolationMode = System.Drawing.Drawing2D.InterpolationMode.HighQualityBicubic;
                graphics.CompositingQuality = System.Drawing.Drawing2D.CompositingQuality.HighQuality;
                graphics.SmoothingMode = System.Drawing.Drawing2D.SmoothingMode.HighQuality;

                // Dibujamos la imagen redimensionada
                graphics.DrawImage(originalImage, 0, 0, width, height);
            }
            return resizedImage;
        }

        //===================PARA CARGAR IMAGENES BASE Y COMPARACION====================
        private void button1_Click(object sender, EventArgs e)
        {
            openFileDialog1.Filter = "Imagenes JPG|*.jpg|Imagenes PNG|*.png";
            if (openFileDialog1.ShowDialog() == DialogResult.OK)
            {
                Bitmap originalImage = new Bitmap(openFileDialog1.FileName);

                // Normalizamos el tamaño de la imagen
                baseImage = ResizeImage(originalImage, pictureBox1.Width, pictureBox1.Height);

                pictureBox1.Image = baseImage;
            }
        }

        private void button4_Click(object sender, EventArgs e)
        {
            openFileDialog1.Filter = "Imagenes JPG|*.jpg|Imagenes PNG|*.png";
            if (openFileDialog1.ShowDialog() == DialogResult.OK)
            {
                Bitmap originalImage = new Bitmap(openFileDialog1.FileName);

                // Normalizamos el tamaño de la imagen
                compareImage = ResizeImage(originalImage, pictureBox3.Width, pictureBox3.Height);

                pictureBox3.Image = compareImage;
            }
        }

        //===============PARA DETERMINAR DEL COLOR DEL GLACIAR========================
        private bool IsGlacier(Color pixel)
        {
            return pixel.R > 200 && pixel.G > 200 && pixel.B > 210; // Blanco/azul claro
        }

        //====================PROCESAR LA IMAGEN PARA COMPARAR====================
        private void button3_Click(object sender, EventArgs e)
        {
            if (baseImage == null || compareImage == null)
            {
                MessageBox.Show("Por favor, carga ambas imágenes.");
                return;
            }

            if (baseImage.Width != compareImage.Width || baseImage.Height != compareImage.Height)
            {
                MessageBox.Show("Las imágenes deben tener las mismas dimensiones.");
                return;
            }

            Bitmap resultImage = new Bitmap(baseImage.Width, baseImage.Height);
            int glacierChangeCount = 0; // Contador de píxeles cambiados
            int glacierPixelCount = 0;  // Contador de píxeles de glaciar
            double percentageChange;
            for (int i = 0; i < baseImage.Width; i++)
            {
                for (int j = 0; j < baseImage.Height; j++)
                {
                    Color basePixel = baseImage.GetPixel(i, j);
                    Color comparePixel = compareImage.GetPixel(i, j);

                    // Analiza solo las áreas de glaciar
                    if (IsGlacier(basePixel))
                    {
                        glacierPixelCount++;

                        // Calcular diferencia en colores
                        int diffR = Math.Abs(basePixel.R - comparePixel.R);
                        int diffG = Math.Abs(basePixel.G - comparePixel.G);
                        int diffB = Math.Abs(basePixel.B - comparePixel.B);

                        // Umbral dinámico
                        if (diffR > umbral || diffG > umbral || diffB > umbral)
                        {
                            resultImage.SetPixel(i, j, Color.Red); // Resaltar cambio en rojo
                            glacierChangeCount++;
                        }
                        else
                        {
                            resultImage.SetPixel(i, j, basePixel); // Mantener píxel original
                        }
                    }
                    else
                    {
                        resultImage.SetPixel(i, j, basePixel);
                    }
                }
            }

            if (glacierPixelCount == 0)
            {
                label7.Text = "No se encontraron píxeles en el glaciar para el análisis.";
                label7.ForeColor = Color.Gray;
            }
            else
            {
                percentageChange = (double)glacierChangeCount / glacierPixelCount * 100;
                label7.Text = "Porcentaje de cambio detectado en el glaciar: " + percentageChange.ToString("F2") + "%";
                label7.ForeColor = percentageChange > 50 ? Color.Red : Color.Green;
            }
            
            pictureBox2.Image = resultImage;
        }

        private void trackBar1_Scroll(object sender, EventArgs e)
        {
            umbral = trackBar1.Value;
            label1.Text = umbral.ToString();
        }

        private void label1_Click(object sender, EventArgs e)
        {

        }

        private void pictureBox1_Click(object sender, EventArgs e)
        {

        }

    }
}
