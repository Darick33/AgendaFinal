import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { RclavePage } from './rclave.page';

const routes: Routes = [
  {
    path: '',
    component: RclavePage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class RclavePageRoutingModule {}
