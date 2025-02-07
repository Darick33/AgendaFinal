import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ActualizarContactoPage } from './actualizar-contacto.page';

const routes: Routes = [
  {
    path: '',
    component: ActualizarContactoPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ActualizarContactoPageRoutingModule {}
