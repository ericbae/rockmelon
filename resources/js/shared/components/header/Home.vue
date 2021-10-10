<template>
	<div class="relative">
    <Desktop />
    <Mobile />
    <MobileNavigation />
    <ConfirmModal />
    <Notification />
    <MessageModal />
    
    <template v-if="getCurrUser != null">
      <FeedbackModal />
    </template>
	</div>
</template>

<script>
  import Desktop                    from './Desktop'
  import Mobile                     from './Mobile'
  import MobileNavigation           from './MobileNavigation'
  import Notification               from '@/shared/components/Notification'
  import ConfirmModal               from '@/shared/components/modals/ConfirmModal'
  import MessageModal               from '@/shared/components/modals/MessageModal'
  import FeedbackModal              from '@/shared/components/modals/FeedbackModal'

	export default {
    components : {
      Desktop,
      Mobile,
      MobileNavigation,
      ConfirmModal,
      Notification,
      MessageModal,
      FeedbackModal
    },

    data() {
      return {

      }
    },
	}
</script>