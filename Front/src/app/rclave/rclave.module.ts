import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { RclavePageRoutingModule } from './rclave-routing.module';

import { RclavePage } from './rclave.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    RclavePageRoutingModule
  ],
  declarations: [RclavePage]
})
export class RclavePageModule {}
