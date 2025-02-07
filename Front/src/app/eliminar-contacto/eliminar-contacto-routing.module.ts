import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { EliminarContactoPage } from './eliminar-contacto.page';

const routes: Routes = [
  {
    path: '',
    component: EliminarContactoPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class EliminarContactoPageRoutingModule {}
