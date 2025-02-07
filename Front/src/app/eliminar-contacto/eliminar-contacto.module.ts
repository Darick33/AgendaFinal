import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { EliminarContactoPageRoutingModule } from './eliminar-contacto-routing.module';

import { EliminarContactoPage } from './eliminar-contacto.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    EliminarContactoPageRoutingModule
  ],
  declarations: [EliminarContactoPage]
})
export class EliminarContactoPageModule {}
