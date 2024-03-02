int etape;

     void afficheChiffre(char chiffre,char position){
      LATA = 0x01 << position;     //   les PINs de AN0 a AN3 sont des canaux analogiques????
      switch(chiffre){
       case 0:LATD0_bit = 1;LATD1_bit = 1;LATD2_bit = 1;LATD3_bit = 1;
              LATD4_bit = 1;LATD5_bit = 1;LATD6_bit = 0;LATD7_bit = 0;
       break;
       case 1:LATD0_bit = 0;LATD1_bit = 1;LATD2_bit = 1;LATD3_bit = 0;
              LATD4_bit = 0;LATD5_bit = 0;LATD6_bit = 0;LATD7_bit = 0;
       break;

       case 2:LATD0_bit = 1;LATD1_bit = 1;LATD2_bit = 0;LATD3_bit = 1;
              LATD4_bit = 1;LATD5_bit = 0;LATD6_bit = 1;LATD7_bit = 0;
       break;
       case 3:LATD0_bit = 1;LATD1_bit = 1;LATD2_bit = 1;LATD3_bit = 1;
              LATD4_bit = 0;LATD5_bit = 0;LATD6_bit = 1;LATD7_bit = 0;
       break;
       case 4:LATD0_bit = 0;LATD1_bit = 1;LATD2_bit = 1;LATD3_bit = 0;
              LATD4_bit = 0;LATD5_bit = 1;LATD6_bit = 1;LATD7_bit = 0;
       break;
       case 5:LATD0_bit = 1;LATD1_bit = 0;LATD2_bit = 1;LATD3_bit = 1;
              LATD4_bit = 0;LATD5_bit = 1;LATD6_bit = 1;LATD7_bit = 0;
       break;
       case 6:LATD0_bit = 1;LATD1_bit = 0;LATD2_bit = 1;LATD3_bit = 1;
              LATD4_bit = 1;LATD5_bit = 1;LATD6_bit = 1;LATD7_bit = 0;
       break;
       case 7:LATD0_bit = 1;LATD1_bit = 1;LATD2_bit = 1;LATD3_bit = 0;
              LATD4_bit = 0;LATD5_bit = 0;LATD6_bit = 0;LATD7_bit = 0;
       break;
       case 8:LATD0_bit = 1;LATD1_bit = 1;LATD2_bit = 1;LATD3_bit = 1;
              LATD4_bit = 1;LATD5_bit = 1;LATD6_bit = 1;LATD7_bit = 0;
       break;
       case 9:LATD0_bit = 1;LATD1_bit = 1;LATD2_bit = 1;LATD3_bit = 1;
              LATD4_bit = 0;LATD5_bit = 1;LATD6_bit = 1;LATD7_bit = 0;
       break;
       case 10:LATD0_bit = 0;LATD1_bit = 0;LATD2_bit = 0;LATD3_bit = 0;
              LATD4_bit = 0;LATD5_bit = 0;LATD6_bit = 0;LATD7_bit = 1;
       break;
       default:
       break;
      }
     }



       void afficheNombre(int var){
      char chiffre0,chiffre1,chiffre2,chiffre3;
      chiffre3 = var/1000;
      var -= chiffre3*1000;
      chiffre2 = var/100;
      var -= chiffre2*100;
      chiffre1 = var/10;
      var -= chiffre1*10;
      chiffre0 = (char)var;
      afficheChiffre(chiffre0,0);
      Delay_ms(1);
      afficheChiffre(chiffre1,1);
      Delay_ms(1);
      afficheChiffre(chiffre2,2);
      Delay_ms(1);
      afficheChiffre(chiffre3,3);
      Delay_ms(1);
     }


    void main(void) {
    int nombreaffiche = 0;
    int compte = 0;
    UART1_Init(9600);
    UART_Write_Text("start");
    UART1_Write(13);
    UART1_Write(10);

    //int timeout;


      ANSELB = 0x00;  ////   toutes les entrées sont numeriques
      TRISA = 0x00;
      TRISC = 0x01;
      TRISD = 0x00;

      LATA = 0x00;
      LATC = 0x00;
      LATD = 0x00;
      etape = 0;

      afficheNombre(nombreaffiche);
      while(1){
        switch(etape) {

         case 0:
         LATC1_bit = 1;
         compte = 0;
         etape++ ;
         break;


         case 1:
              Delay_ms(1);
              LATC1_bit = 0;
              etape++ ;

              break;
         case 2:

                 if(PORTC & 0x01){
                    while(PORTC & 0x01){
                                compte++;
                                delay_us(10);
                                }
                                etape++;
                                 }
                                break;

         case 3:
          nombreaffiche = (int)compte*10/58.0;
           Delay_ms(1);
                    UART1_Write(nombreaffiche);
                    delay_ms(100);

         etape=0;
         break;

        }
           if(etape == 3)afficheNombre(nombreaffiche);

      }
    }