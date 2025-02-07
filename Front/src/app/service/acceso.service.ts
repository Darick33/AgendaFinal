import { Injectable } from '@angular/core';
import { AlertController, LoadingController, ToastController } from '@ionic/angular';
import { Preferences } from '@capacitor/preferences';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { environment } from 'src/environments/environment.prod';
import { catchError, throwError } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AccesoService {
  server: string = 'http://localhost/Ws_agenda2024/agenda.php';
  constructor(
    public ToastCTL: ToastController,
    public  LoadingCRTL: LoadingController,
    public  AlertCRTL: AlertController,
    public  http: HttpClient,
    

  ) { }
  postData(body: any) {
  const head = new HttpHeaders({
    'Content-Type': 'application/json',
    Accept: 'application/json',
  });
  const options = { headers: head };
  return this.http.post(this.server, body, options).pipe(
    catchError((error) => {
      console.error('Error al procesar la solicitud', error);
      return throwError(error); // Re-throws the error for further handling
    })
  );
}


  async showToast(message: string, duration: number, tipe: string) {
    const toast = await this.ToastCTL.create({
      message,
      duration,
      position: 'top',
      color: tipe,
    mode: 'ios',
    animated: true,
    cssClass: 'custom-toast',

    });
    await toast.present();
  }
  async createSession(id:string, valor:string) {
    await Preferences.set({
      key: id,
      value: valor
    });
  }
  async getSession(id:string) {
    const item = await Preferences.get({ key: id });
    return item.value;
  }
  async closeSession() {
    await Preferences.clear();
  }
}
