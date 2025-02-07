import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ActualizarContactoPageRoutingModule } from './actualizar-contacto-routing.module';

import { ActualizarContactoPage } from './actualizar-contacto.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ActualizarContactoPageRoutingModule
  ],
  declarations: [ActualizarContactoPage]
})
export class ActualizarContactoPageModule {}
